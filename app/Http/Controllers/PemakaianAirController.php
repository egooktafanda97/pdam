<?php

namespace App\Http\Controllers;

use App\Models\PemakaianAir;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemakaianAirController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        
        $query = PemakaianAir::with('pelanggan.golonganTarif')
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun);

        if (Auth::user()->hasRole('petugas')) {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('petugas_id', Auth::id());
            });
        }
            
        $pemakaian = $query->orderBy('created_at', 'desc')->get();
            
        return view('pemakaian.index', compact('pemakaian', 'bulan', 'tahun'));
    }

    public function create()
    {
        $bulan = date('n');
        $tahun = date('Y');
        
        // Tampilkan semua pelanggan aktif (jika petugas, hanya yang di-assign ke dia)
        $query = Pelanggan::where('is_active', true);

        if (Auth::user()->hasRole('petugas')) {
            $query->where('petugas_id', Auth::id());
        }

        $pelanggan = $query->get();
        
        return view('pemakaian.create', compact('pelanggan', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer|min:2020',
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|gte:meter_awal',
        ]);

        $pelanggan = Pelanggan::with('golonganTarif')->findOrFail($request->pelanggan_id);

        // Cek apakah sudah ada input bulan ini
        $exists = PemakaianAir::where('pelanggan_id', $pelanggan->id)
            ->where('periode_bulan', $request->periode_bulan)
            ->where('periode_tahun', $request->periode_tahun)
            ->exists();
            
        if ($exists) {
            return back()->withInput()->with('error', 'Pemakaian air untuk pelanggan ini pada periode tersebut sudah dicatat.');
        }

        $total_pemakaian = $request->meter_akhir - $request->meter_awal;

        $pemakaian = PemakaianAir::create([
            'pelanggan_id' => $request->pelanggan_id,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'total_pemakaian' => $total_pemakaian,
            'petugas_id' => Auth::id(),
        ]);

        // Langsung generate tagihan
        $tarif_per_m3 = $pelanggan->golonganTarif->tarif_per_m3 ?? 0;
        $biaya_admin = $pelanggan->golonganTarif->biaya_admin ?? 2500;
        $biaya_pemakaian = $total_pemakaian * $tarif_per_m3;
        $total_tagihan = $biaya_pemakaian + $biaya_admin;
        
        // Tanggal jatuh tempo bulan depan tanggal 20
        $tanggal_jatuh_tempo = Carbon::createFromDate($request->periode_tahun, $request->periode_bulan, 20)->addMonth()->format('Y-m-d');

        Tagihan::create([
            'pemakaian_air_id' => $pemakaian->id,
            'pelanggan_id' => $pelanggan->id,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'jumlah_meter' => $total_pemakaian,
            'biaya_pemakaian' => $biaya_pemakaian,
            'biaya_admin' => $biaya_admin,
            'total_tagihan' => $total_tagihan,
            'status' => 'Belum Bayar',
            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo
        ]);

        return redirect()->route('petugas.pemakaian.index')->with('success', 'Pemakaian air berhasil dicatat dan tagihan otomatis dibuat.');
    }

    public function show(PemakaianAir $pemakaianAir)
    {
        return abort(404);
    }

    /**
     * API: Ambil meter_akhir terakhir dari pelanggan untuk dijadikan meter_awal.
     */
    public function getLastMeter($id)
    {
        $lastPemakaian = PemakaianAir::where('pelanggan_id', $id)
            ->orderByRaw('periode_tahun DESC, periode_bulan DESC')
            ->first();

        if ($lastPemakaian) {
            return response()->json([
                'meter_awal' => $lastPemakaian->meter_akhir,
                'periode' => $lastPemakaian->periode_bulan . '/' . $lastPemakaian->periode_tahun,
            ]);
        }

        // Pelanggan baru, belum ada riwayat pemakaian
        return response()->json([
            'meter_awal' => 0,
            'periode' => null,
        ]);
    }
}
