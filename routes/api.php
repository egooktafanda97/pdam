<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PelangganDashboardController;
use App\Http\Controllers\Api\PelangganTagihanController;
use App\Http\Controllers\Api\PelangganPembayaranController;
use App\Http\Controllers\Api\PelangganPengaduanController;
use App\Http\Controllers\Api\PelangganProfilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth Endpoint (Public)
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/pelanggan/dashboard', [PelangganDashboardController::class, 'index']);

    // Profil
    Route::get('/pelanggan/profil', [PelangganProfilController::class, 'show']);
    Route::put('/pelanggan/profil/password', [PelangganProfilController::class, 'updatePassword']);

    // Tagihan
    Route::get('/pelanggan/tagihan', [PelangganTagihanController::class, 'index']);
    Route::get('/pelanggan/tagihan/{id}', [PelangganTagihanController::class, 'show']);

    // Pembayaran
    Route::post('/pelanggan/bayar/{tagihan_id}', [PelangganPembayaranController::class, 'bayar']);
    Route::get('/pelanggan/riwayat', [PelangganPembayaranController::class, 'riwayat']);
    Route::get('/pelanggan/pembayaran/{id}', [PelangganPembayaranController::class, 'show']);
    Route::post('/pelanggan/pembayaran/{id}/cancel', [PelangganPembayaranController::class, 'cancel']);

    // Pengaduan
    Route::get('/pelanggan/pengaduan', [PelangganPengaduanController::class, 'index']);
    Route::post('/pelanggan/pengaduan', [PelangganPengaduanController::class, 'store']);
    Route::get('/pelanggan/pengaduan/{id}', [PelangganPengaduanController::class, 'show']);
});

// iPaymu Callback (Public)
Route::post('/ipaymu/callback', [PelangganPembayaranController::class, 'callback']);
