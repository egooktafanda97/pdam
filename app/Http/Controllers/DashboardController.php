<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use App\Models\PemakaianAir;
use App\Models\Tagihan;
use App\Models\Pengaduan;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('petugas')) {
            return redirect()->route('petugas.dashboard');
        } elseif ($user->hasRole('pelanggan')) {
            return redirect()->route('pelanggan.dashboard');
        } elseif ($user->hasRole('pimpinan')) {
            return redirect()->route('pimpinan.dashboard');
        }

        return view('dashboard');
    }

    public function admin()
    {
        $data = [
            'total_pelanggan' => Pelanggan::count(),
            'pemakaian_bulan_ini' => PemakaianAir::where('periode_bulan', date('n'))
                ->where('periode_tahun', date('Y'))
                ->sum('total_pemakaian') ?? 0,
            'sudah_bayar' => Pembayaran::whereMonth('tanggal_bayar', date('m'))
                ->whereYear('tanggal_bayar', date('Y'))
                ->where('status_pembayaran', 'Sukses')
                ->count(),
            'aduan_baru' => Pengaduan::where('status', 'Baru')->count(),
            'tagihan_terbaru' => Tagihan::with('pelanggan')->orderBy('created_at', 'desc')->take(5)->get(),
            'pengaduan_terbaru' => Pengaduan::with('pelanggan')->orderBy('created_at', 'desc')->take(5)->get(),
            'pembayaran_terakhir' => Pembayaran::with('tagihan.pelanggan')->orderBy('created_at', 'desc')->take(5)->get(),
        ];
        return view('dashboard.admin', compact('data'));
    }

    public function petugas()
    {
        $userId = Auth::id();
        
        $data = [
            'pelanggan_aktif' => Pelanggan::where('is_active', true)->where('petugas_id', $userId)->count(),
            'belum_diinput' => Pelanggan::where('petugas_id', $userId)->whereDoesntHave('pemakaianAir', function ($query) {
                $query->where('periode_bulan', date('n'))->where('periode_tahun', date('Y'));
            })->count(),
            'tagihan_belum_lunas' => Tagihan::whereHas('pelanggan', function($q) use ($userId) {
                $q->where('petugas_id', $userId);
            })->whereIn('status', ['Belum Bayar', 'Pending'])->count(),
            'aduan_baru' => Pengaduan::whereHas('pelanggan', function($q) use ($userId) {
                $q->where('petugas_id', $userId);
            })->where('status', 'Baru')->count(),
        ];
        return view('dashboard.petugas', compact('data'));
    }

    public function pelanggan()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        if (!$pelanggan) {
            abort(403, 'Akses ditolak: Anda tidak memiliki relasi data pelanggan.');
        }

        $tagihanAktif = Tagihan::with('pemakaianAir')->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['Belum Bayar', 'Pending'])->latest()->first();
            
        $pemakaianBulanIni = PemakaianAir::where('pelanggan_id', $pelanggan->id)
            ->where('periode_bulan', date('n'))
            ->where('periode_tahun', date('Y'))
            ->first();

        $riwayatPembayaran = Pembayaran::with('tagihan')->whereHas('tagihan', function($q) use ($pelanggan) {
            $q->where('pelanggan_id', $pelanggan->id);
        })->orderBy('tanggal_bayar', 'desc')->take(3)->get();

        return view('dashboard.pelanggan', compact('pelanggan', 'tagihanAktif', 'pemakaianBulanIni', 'riwayatPembayaran'));
    }

    public function pimpinan()
    {
        $data = [
            'total_pendapatan' => Pembayaran::where('status_pembayaran', 'Sukses')->sum('jumlah_bayar'),
            'total_pelanggan' => Pelanggan::count(),
            'aduan_selesai' => Pengaduan::where('status', 'Selesai')->count(),
        ];
        return view('dashboard.pimpinan', compact('data'));
    }
}
