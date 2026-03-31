<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    // ==========================================
    // BAGIAN PELANGGAN (Resource: index, create, store, show)
    // ==========================================
    
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika pelanggan, tampilkan riwayat aduan milik pelanggan
        if ($user->hasRole('pelanggan')) {
            $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
            if (!$pelanggan) {
                abort(403, 'Akses ditolak: Anda tidak memiliki data pelanggan yang valid.');
            }

            $pengaduan = Pengaduan::where('pelanggan_id', $pelanggan->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('pengaduan.riwayat', compact('pengaduan'));
        }

        // Admin/Petugas: lihat semua aduan
        $status = $request->get('status', 'Semua');
        
        $query = Pengaduan::with('pelanggan')->orderBy('created_at', 'desc');
        
        if ($user->hasRole('petugas')) {
            $query->whereHas('pelanggan', function ($q) {
                $q->where('petugas_id', Auth::id());
            });
        }

        if ($status != 'Semua') {
            $query->where('status', $status);
        }

        $pengaduan = $query->paginate(15);
        $pengaduan->appends(['status' => $status]);

        return view('pengaduan.index', compact('pengaduan', 'status'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        if (!$pelanggan) {
            abort(403, 'Akses ditolak.');
        }

        return view('pengaduan.create', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $pelanggan = Pelanggan::where('user_id', Auth::id())->first();
        if (!$pelanggan) {
            abort(403, 'Akses ditolak.');
        }
        
        $request->validate([
            'kategori' => 'required|string',
            'judul_pengaduan' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        Pengaduan::create([
            'pelanggan_id' => $pelanggan->id,
            'kategori' => $request->kategori,
            'judul_pengaduan' => $request->judul_pengaduan,
            'deskripsi' => $request->deskripsi,
            'tanggal_pengaduan' => now(),
            'status' => 'Baru',
        ]);

        return redirect()->route('pelanggan.pengaduan.index')->with('success', 'Pengaduan berhasil dikirim. Kami akan segera menindaklanjutinya.');
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with('pelanggan', 'petugas')->findOrFail($id);
        return view('pengaduan.show', compact('pengaduan'));
    }

    // ==========================================
    // BAGIAN ADMIN & PETUGAS (Respon)
    // ==========================================

    public function respon(Request $request, $id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Baru,Diproses,Selesai',
            'tanggapan' => 'required|string'
        ]);

        $pengaduan->update([
            'status' => $request->status,
            'tanggapan' => $request->tanggapan,
            'tanggal_tanggapan' => ($request->status == 'Selesai') ? now() : null,
            'petugas_id' => Auth::id(),
        ]);

        return redirect()->route('admin.pengaduan.show', $pengaduan->id)->with('success', 'Status pengaduan berhasil diperbarui dan tanggapan telah dikirim.');
    }
}
