<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    // ====== LOKET (Admin/Petugas) ====== //
    public function loket($tagihan_id)
    {
        $tagihan = Tagihan::with('pelanggan.golonganTarif', 'pemakaianAir')->findOrFail($tagihan_id);
        
        // Petugas only can pay for their assigned customers
        if (Auth::user()->hasRole('petugas') && $tagihan->pelanggan->petugas_id !== Auth::id()) {
            abort(403, 'Akses ditolak: Anda hanya dapat memproses tagihan milik pelanggan yang Anda data.');
        }

        if ($tagihan->status == 'Lunas') {
            return redirect()->route('petugas.tagihan')->with('error', 'Tagihan ini sudah lunas.');
        }

        return view('pembayaran.create', compact('tagihan'));
    }

    public function bayarLoket(Request $request, $tagihan_id)
    {
        $tagihan = Tagihan::with('pelanggan')->findOrFail($tagihan_id);

        if (Auth::user()->hasRole('petugas') && $tagihan->pelanggan->petugas_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($tagihan->status == 'Lunas') {
            return redirect()->route('petugas.tagihan')->with('error', 'Tagihan ini sudah lunas.');
        }

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:' . $tagihan->total_tagihan,
        ]);

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'petugas_id' => Auth::id(),
            'tanggal_bayar' => now(),
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode_bayar' => 'Loket',
            'status_pembayaran' => 'Sukses',
            'kode_pembayaran' => 'LKT-' . date('YmdHis') . '-' . rand(100, 999),
        ]);

        $tagihan->update(['status' => 'Lunas']);

        return redirect()->route('petugas.pembayaran.cetak', $pembayaran->id)->with('success', 'Pembayaran berhasil diproses.');
    }

    public function cetak($pembayaran_id)
    {
        $pembayaran = Pembayaran::with(['tagihan.pelanggan.golonganTarif', 'tagihan.pemakaianAir', 'petugas'])->findOrFail($pembayaran_id);

        return view('pembayaran.cetak', compact('pembayaran'));
    }

    // ====== ONLINE (Pelanggan) ====== //
    public function online($tagihan_id)
    {
        $tagihan = Tagihan::with('pelanggan.golonganTarif', 'pemakaianAir')->findOrFail($tagihan_id);

        if ($tagihan->pelanggan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($tagihan->status == 'Lunas') {
            return redirect()->route('pelanggan.tagihan')->with('error', 'Tagihan ini sudah lunas.');
        }
        
        // Cari jika ada transaksi pending sebelumnya
        $pembayaranPending = Pembayaran::where('tagihan_id', $tagihan->id)
                                ->where('status_pembayaran', 'Pending')
                                ->orderBy('created_at', 'desc')
                                ->first();

        return view('pembayaran.online', compact('tagihan', 'pembayaranPending'));
    }

    public function bayarOnline(Request $request, $tagihan_id)
    {
        $tagihan = Tagihan::with('pelanggan')->findOrFail($tagihan_id);

        if ($tagihan->pelanggan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'payment_method' => 'required|string',
        ]);

        // Batalkan pending lama
        $existingPending = Pembayaran::where('tagihan_id', $tagihan->id)
                                ->where('status_pembayaran', 'Pending')
                                ->first();
        if ($existingPending) {
            $existingPending->update(['status_pembayaran' => 'Gagal']);
        }

        // Mockup integrasi gateway
        $referensi = 'VA-' . strtoupper($request->payment_method) . '-' . date('YmdHis');
        $payment_code = ($request->payment_method == 'QRIS') 
            ? 'QRIS-' . rand(100000, 999999) 
            : '8839' . $tagihan->pelanggan->nomor_pelanggan . rand(10, 99);

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'tanggal_bayar' => now(),
            'jumlah_bayar' => $tagihan->total_tagihan,
            'metode_bayar' => 'Transfer Bank/QRIS',
            'penyedia_layanan' => $request->payment_method,
            'status_pembayaran' => 'Pending',
            'kode_pembayaran' => $payment_code,
            'referensi_gateway' => $referensi,
        ]);

        $tagihan->update(['status' => 'Pending']);

        return back()->with('success', 'Permintaan pembayaran diteruskan. Silahkan selesaikan pembayaran.')
                     ->with('payment_data', [
                         'method' => $request->payment_method,
                         'code' => $payment_code,
                         'amount' => $tagihan->total_tagihan,
                         'limit' => now()->addHours(24)->format('d/m/Y H:i')
                     ]);
    }

    // Riwayat Pembayaran Pelanggan
    public function riwayat()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        if (!$pelanggan) {
            abort(403, 'Akses ditolak.');
        }

        $pembayaran = Pembayaran::with('tagihan')
            ->whereHas('tagihan', function($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->paginate(10);

        return view('pembayaran.riwayat', compact('pembayaran'));
    }

    // ====== ADMIN: HISTORY ONLINE DARI GATEWAY ====== //
    public function indexGateway()
    {
        $pembayaran = Pembayaran::with('tagihan.pelanggan')
            ->where('penyedia_layanan', '!=', 'Loket')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.pembayaran_gateway.index', compact('pembayaran'));
    }
}
