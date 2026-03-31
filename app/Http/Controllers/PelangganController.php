<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\GolonganTarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with(['user', 'golonganTarif', 'petugas'])->orderBy('created_at', 'desc')->get();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $golongan = GolonganTarif::all();
        $list_petugas = User::where('role', '!=', 'pelanggan')->get();
        
        return view('pelanggan.create', compact('golongan', 'list_petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:8',
            'golongan_tarif_id' => 'required|exists:golongan_tarif,id',
            'petugas_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'nomor_meter' => 'required|string|max:50|unique:pelanggan',
            'no_telepon' => 'nullable|string|max:20',
            'foto_ktp' => 'nullable|image|max:2048',
            'koordinat' => 'nullable|string'
        ]);

        $data = $request->except('foto_ktp', 'username', 'password');
        
        // Buat User Akun Pelanggan
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);
        $user->assignRole('pelanggan');

        // Sambungkan ke Pelanggan
        $data['user_id'] = $user->id;

        // Generate nomor pelanggan
        $data['nomor_pelanggan'] = 'PLG' . date('Ymd') . rand(100, 999);

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('pelanggan/ktp', 'public');
        }

        Pelanggan::create($data);

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan beserta Akun Login berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load('user', 'golonganTarif');
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        $golongan = GolonganTarif::all();
        $list_petugas = User::where('role', '!=', 'pelanggan')->get();
        
        return view('pelanggan.edit', compact('pelanggan', 'golongan', 'list_petugas'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'golongan_tarif_id' => 'required|exists:golongan_tarif,id',
            'petugas_id' => 'nullable|exists:users,id',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'nomor_meter' => 'required|string|max:50|unique:pelanggan,nomor_meter,' . $pelanggan->id,
            'no_telepon' => 'nullable|string|max:20',
            'foto_ktp' => 'nullable|image|max:2048',
            'koordinat' => 'nullable|string'
        ]);

        $data = $request->except('foto_ktp');

        if ($request->hasFile('foto_ktp')) {
            if ($pelanggan->foto_ktp && Storage::disk('public')->exists($pelanggan->foto_ktp)) {
                Storage::disk('public')->delete($pelanggan->foto_ktp);
            }
            $data['foto_ktp'] = $request->file('foto_ktp')->store('pelanggan/ktp', 'public');
        }

        $pelanggan->update($data);
        
        // Update User Name to match
        if ($pelanggan->user) {
            $pelanggan->user->update(['name' => $request->nama]);
        }

        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ($pelanggan->foto_ktp && Storage::disk('public')->exists($pelanggan->foto_ktp)) {
            Storage::disk('public')->delete($pelanggan->foto_ktp);
        }
        
        $pelanggan->delete();
        
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}
