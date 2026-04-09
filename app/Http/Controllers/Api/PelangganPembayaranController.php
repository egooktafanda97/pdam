<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Pelanggan;

class PelangganPembayaranController extends Controller
{
    public function bayar(Request $request, $tagihan_id)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Akun Anda belum terhubung dengan data Pelanggan'], 404);
        }

        $tagihan = Tagihan::where('id', $tagihan_id)
            ->where('pelanggan_id', $pelanggan->id)
            ->first();

        if (!$tagihan) {
            return response()->json(['message' => 'Tagihan tidak ditemukan atau bukan milik Anda'], 403);
        }

        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $existingPending = Pembayaran::where('tagihan_id', $tagihan->id)
            ->where('status_pembayaran', 'Pending')
            ->first();
            
        if ($existingPending) {
            $existingPending->update(['status_pembayaran' => 'Gagal']);
        }

        // Tentukan paymentMethod dan paymentChannel berdasarkan pilihan user
        $methodMap = [
            'BNI'     => ['method' => 'va', 'channel' => 'bni'],
            'BRI'     => ['method' => 'va', 'channel' => 'bri'],
            'MANDIRI' => ['method' => 'va', 'channel' => 'mandiri'],
            'BCA'     => ['method' => 'va', 'channel' => 'bca'],
            'QRIS'    => ['method' => 'qris', 'channel' => 'qris'],
        ];

        $selected = $methodMap[$request->payment_method] ?? ['method' => 'va', 'channel' => strtolower($request->payment_method)];

        // Call iPaymu API
        $ipaymu = new \App\Services\IpaymuService();
        $result = $ipaymu->createDirectPayment([
            'name'           => $pelanggan->nama,
            'phone'          => $pelanggan->no_telepon ?? '081200000000',
            'email'          => $user->email ?? 'pelanggan@pdam.com',
            'amount'         => (int) $tagihan->total_tagihan,
            'paymentMethod'  => $selected['method'],
            'paymentChannel' => $selected['channel'],
            'referenceId'    => 'PDAM-' . $tagihan->id . '-' . date('YmdHis'),
            'expired'        => 24,
        ]);

        if (!$result['success']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'] ?? 'Gagal memproses pembayaran via iPaymu',
            ], 422);
        }

        $payment_code = $result['payment_no'] ?? ($result['qr_url'] ?? '-');
        $reference_sent = $result['reference_id'] ?? '-';
        $transaction_id = (string) ($result['transaction_id'] ?? '-');
        $expired_at = isset($result['expired']) ? \Carbon\Carbon::parse($result['expired']) : now()->addHours(24);

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => now(),
            'jumlah_bayar' => $tagihan->total_tagihan,
            'metode_bayar' => 'Transfer Bank/QRIS',
            'penyedia_layanan' => $request->payment_method,
            'status_pembayaran' => 'Pending',
            'kode_pembayaran' => $payment_code,
            'referensi_gateway' => $reference_sent,
            'id_transaksi' => $transaction_id,
            'expired_at' => $expired_at,
        ]);

        $tagihan->update(['status' => 'Pending']);

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan pembayaran berhasil.',
            'data' => [
                'pembayaran' => $pembayaran,
                'payment_info' => [
                    'method' => $result['payment_name'] ?? $request->payment_method,
                    'code' => $payment_code,
                    'transaction_id' => $transaction_id,
                    'reference_id' => $reference_sent,
                    'amount' => $tagihan->total_tagihan,
                    'limit' => $result['expired'] ?? now()->addHours(24)->format('Y-m-d H:i:s'),
                    'qr_url' => $result['qr_url'] ?? null,
                ]
            ]
        ]);
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'current_page' => 1,
                    'data' => [],
                    'total' => 0
                ]
            ]);
        }

        $pembayaran = Pembayaran::with('tagihan')
            ->whereHas('tagihan', function($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $pembayaran
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pembayaran = Pembayaran::with('tagihan')
            ->whereHas('tagihan', function($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->where('id', $id)
            ->first();
            
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pembayaran
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }

        $pembayaran = Pembayaran::with('tagihan')
            ->whereHas('tagihan', function($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->where('id', $id)
            ->first();
            
        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan via ID tersebut'], 404);
        }

        if ($pembayaran->status_pembayaran !== 'Pending') {
            return response()->json(['message' => 'Hanya pembayaran Pending yang dapat dibatalkan'], 400);
        }

        $pembayaran->update(['status_pembayaran' => 'Batal']);

        if ($pembayaran->tagihan) {
            $pembayaran->tagihan->update(['status' => 'Belum Bayar']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil dibatalkan.'
        ]);
    }

    public function callback(Request $request)
    {
        // Log callback data
        \Illuminate\Support\Facades\Log::info('iPaymu Callback', $request->all());

        $sid = $request->sid;
        $status = $request->status; // 'berhasil' or other

        if ($status == 'berhasil') {
            $pembayaran = Pembayaran::where('referensi_gateway', $sid)->first();
            if ($pembayaran) {
                $pembayaran->update(['status_pembayaran' => 'Sukses']);
                // Update tagihan juga
                if ($pembayaran->tagihan) {
                    $pembayaran->tagihan->update(['status' => 'Lunas']);
                }
                return response()->json(['message' => 'Status updated']);
            }
        }

        return response()->json(['message' => 'Callback received']);
    }
}
