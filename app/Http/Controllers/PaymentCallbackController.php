<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GatewayLog;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('iPaymu Callback Received', $request->all());

        $payload = $request->all();
        
        $log = GatewayLog::create([
            'reference_id' => $payload['reference_id'] ?? null,
            'transaction_id' => $payload['trx_id'] ?? null,
            'status' => $payload['status'] ?? null,
            'payload' => $payload,
        ]);

        if (isset($payload['status']) && strtolower($payload['status']) === 'berhasil') {
            $referenceId = $payload['reference_id'] ?? '';
            
            // Format referensi kita: PDAM-{tagihan_id}-{timestamp}
            // Kita bisa mengekstrak ID tagihan, atau mencari pembayaran berdasarkan referensi_gateway / transaction_id
            $pembayaran = Pembayaran::where('referensi_gateway', $payload['trx_id'] ?? '')
                ->orWhereHas('tagihan', function($q) use ($referenceId) {
                    // Jika referenceId diawali dengan PDAM, kita bisa coba cocokin
                    // Tapi lebih aman query berdasar referensi_gateway yang saat create di set ke transaction_id
                })->first();

            // Cara paling aman, kita cari dari tabel pembayaran yang TransactionID nya direkam di kolom referensi_gateway
            $pembayaran = Pembayaran::where('referensi_gateway', $payload['trx_id'] ?? '')->first();

            if ($pembayaran && $pembayaran->status_pembayaran !== 'Sukses') {
                $pembayaran->update([
                    'status_pembayaran' => 'Sukses',
                    'tanggal_bayar' => now(),
                ]);

                $pembayaran->tagihan->update([
                    'status' => 'Lunas'
                ]);

                Log::info('Payment auto-completed via Webhook', ['pembayaran_id' => $pembayaran->id]);
            }
        }
        return response()->json(['success' => true]);
    }
}
