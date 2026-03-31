<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    // Untuk Admin / Petugas
    public function index(Request $request)
    {
        $status = $request->get('status', 'Semua');
        $bulan = $request->get('bulan', '');
        $tahun = $request->get('tahun', '');

        $query = Tagihan::with('pelanggan', 'pemakaianAir')->orderBy('created_at', 'desc');

        if (Auth::user()->hasRole('petugas')) {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('petugas_id', Auth::id());
            });
        }

        if ($status != 'Semua') {
            $query->where('status', $status);
        }

        if ($bulan != '' && $tahun != '') {
            $query->where('periode_bulan', $bulan)->where('periode_tahun', $tahun);
        }

        $tagihan = $query->get();

        return view('tagihan.index', compact('tagihan', 'status', 'bulan', 'tahun'));
    }

    // Untuk Pelanggan
    public function pelanggan(Request $request)
    {
        $status = $request->get('status', 'Semua');
        
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        if (!$pelanggan) {
            abort(403, 'Akses ditolak: Anda tidak memiliki data pelanggan yang valid.');
        }

        $query = Tagihan::with('pemakaianAir', 'pembayaran')
            ->where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc');

        if ($status != 'Semua') {
            if ($status == 'Belum') {
                $query->whereIn('status', ['Belum Bayar', 'Pending']);
            } elseif ($status == 'Lunas') {
                $query->where('status', 'Lunas');
            }
        }

        $tagihan = $query->paginate(10);

        return view('tagihan.pelanggan', compact('tagihan', 'status'));
    }
}
