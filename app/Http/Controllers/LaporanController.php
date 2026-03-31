<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\PemakaianAir;
use App\Models\GolonganTarif;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function pembayaran(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $pembayaran = Pembayaran::with(['tagihan.pelanggan.golonganTarif'])
            ->where('status_pembayaran', 'Sukses')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
            
        $total_pendapatan = $pembayaran->sum('jumlah_bayar');

        return view('laporan.pembayaran', compact('pembayaran', 'bulan', 'tahun', 'total_pendapatan'));
    }

    public function pelanggan(Request $request)
    {
        $golongan_id = $request->get('golongan_id', 'Semua');
        
        $query = Pelanggan::with('golonganTarif')->orderBy('created_at', 'desc');
        
        if ($golongan_id != 'Semua') {
            $query->where('golongan_tarif_id', $golongan_id);
        }
        
        $pelanggan = $query->get();
        $golongan = GolonganTarif::all();

        return view('laporan.pelanggan', compact('pelanggan', 'golongan_id', 'golongan'));
    }

    public function pemakaian(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        
        $pemakaian = PemakaianAir::with('pelanggan.golonganTarif')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $total_volume = $pemakaian->sum('total_pemakaian');

        return view('laporan.pemakaian', compact('pemakaian', 'bulan', 'tahun', 'total_volume'));
    }
}
