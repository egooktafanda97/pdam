<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();

            // Hanya izinkan pelanggan
            if (!$user->hasRole('pelanggan')) {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hanya pelanggan yang dapat login melalui aplikasi ini.'
                ], 403);
            }

            if (!$user->is_active) {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda telah dinonaktifkan.'
                ], 403);
            }

            $pelanggan = Pelanggan::where('user_id', $user->id)->first();
            
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user,
                'pelanggan' => $pelanggan
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Username atau password salah.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->with('golonganTarif')->first();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'pelanggan' => $pelanggan
        ]);
    }
}
