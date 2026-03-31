<?php

namespace App\Http\Controllers;

use App\Models\GolonganTarif;
use Illuminate\Http\Request;

class GolonganTarifController extends Controller
{
    public function index()
    {
        $golongan = GolonganTarif::orderBy('created_at', 'desc')->get();
        return view('golongan_tarif.index', compact('golongan'));
    }

    public function create()
    {
        return view('golongan_tarif.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_golongan' => 'required|string|max:20|unique:golongan_tarif',
            'nama_golongan' => 'required|string|max:100',
            'tarif_per_m3' => 'required|numeric|min:0',
            'biaya_admin' => 'nullable|numeric|min:0',
        ]);

        GolonganTarif::create($request->all());

        return redirect()->route('admin.golongan_tarif.index')->with('success', 'Golongan tarif berhasil ditambahkan.');
    }

    public function edit(GolonganTarif $golonganTarif)
    {
        return view('golongan_tarif.edit', compact('golonganTarif'));
    }

    public function update(Request $request, GolonganTarif $golonganTarif)
    {
        $request->validate([
            'kode_golongan' => 'required|string|max:20|unique:golongan_tarif,kode_golongan,' . $golonganTarif->id,
            'nama_golongan' => 'required|string|max:100',
            'tarif_per_m3' => 'required|numeric|min:0',
            'biaya_admin' => 'nullable|numeric|min:0',
        ]);

        $golonganTarif->update($request->all());

        return redirect()->route('admin.golongan_tarif.index')->with('success', 'Golongan tarif berhasil diperbarui.');
    }

    public function destroy(GolonganTarif $golonganTarif)
    {
        // Cek apakah golongan ini dipakai oleh pelanggan
        if ($golonganTarif->pelanggan()->count() > 0) {
            return redirect()->route('admin.golongan_tarif.index')->with('error', 'Golongan tarif tidak dapat dihapus karena sedang digunakan oleh pelanggan.');
        }

        $golonganTarif->delete();
        
        return redirect()->route('admin.golongan_tarif.index')->with('success', 'Golongan tarif berhasil dihapus.');
    }
}
