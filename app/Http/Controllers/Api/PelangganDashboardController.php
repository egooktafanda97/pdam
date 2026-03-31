<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\PemakaianAir;
use App\Models\Tagihan;
use App\Models\Pembayaran;

class PelangganDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->with('golonganTarif')->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Data pelanggan tidak ditemukan'], 404);
        }

        $tagihanAktif = Tagihan::with('pemakaianAir')
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['Belum Bayar', 'Pending'])
            ->latest()
            ->first();
            
        $pemakaianBulanIni = PemakaianAir::where('pelanggan_id', $pelanggan->id)
            ->where('periode_bulan', date('n'))
            ->where('periode_tahun', date('Y'))
            ->first();

        $riwayatPembayaran = Pembayaran::with('tagihan')
            ->whereHas('tagihan', function($q) use ($pelanggan) {
                $q->where('pelanggan_id', $pelanggan->id);
            })
            ->orderBy('tanggal_bayar', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'pelanggan' => $pelanggan,
                'tagihan_aktif' => $tagihanAktif,
                'pemakaian_bulan_ini' => $pemakaianBulanIni,
                'riwayat_pembayaran' => $riwayatPembayaran,
            ]
        ]);
    }
}
