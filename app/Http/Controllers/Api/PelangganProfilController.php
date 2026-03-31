<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;

class PelangganProfilController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->with('golonganTarif')->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'pelanggan' => $pelanggan
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password saat ini tidak cocok.'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diperbarui.'
        ]);
    }
}
