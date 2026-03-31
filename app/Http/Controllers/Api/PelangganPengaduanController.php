<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Pelanggan;

class PelangganPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak valid'], 403);
        }

        $pengaduan = Pengaduan::where('pelanggan_id', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $pengaduan
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        $request->validate([
            'kategori' => 'required|string',
            'judul_pengaduan' => 'required|string|max:150',
            'deskripsi' => 'required|string',
        ]);

        $pengaduan = Pengaduan::create([
            'pelanggan_id' => $pelanggan->id,
            'kategori' => $request->kategori,
            'judul_pengaduan' => $request->judul_pengaduan,
            'deskripsi' => $request->deskripsi,
            'tanggal_pengaduan' => now(),
            'status' => 'Baru',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil dikirim.',
            'data' => $pengaduan
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        $pengaduan = Pengaduan::with('petugas')->where('pelanggan_id', $pelanggan->id)
            ->where('id', $id)
            ->first();

        if (!$pengaduan) {
            return response()->json(['message' => 'Detail pengaduan tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $pengaduan
        ]);
    }
}
