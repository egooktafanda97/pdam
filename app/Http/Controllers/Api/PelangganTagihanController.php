<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pelanggan;

class PelangganTagihanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'Semua');
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();
        
        if (!$pelanggan) {
            return response()->json(['message' => 'Data pelanggan tidak ditemukan'], 404);
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

        return response()->json([
            'status' => 'success',
            'data' => $tagihan
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();
        
        $tagihan = Tagihan::with('pemakaianAir', 'pembayaran')
            ->where('pelanggan_id', $pelanggan->id)
            ->where('id', $id)
            ->first();

        if (!$tagihan) {
            return response()->json(['message' => 'Tagihan tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tagihan
        ]);
    }
}
