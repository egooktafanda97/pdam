<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GolonganTarifController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianAirController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PaymentCallbackController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::resource('admin/users', UserController::class)->names('admin.users');
        Route::resource('admin/golongan-tarif', GolonganTarifController::class)->names('admin.golongan_tarif');
        
        // iPaymu Gateway
        Route::get('/admin/gateway-logs', [\App\Http\Controllers\GatewayLogController::class, 'index'])->name('admin.gateway_logs.index');
        Route::get('/admin/pembayaran-online', [PembayaranController::class, 'indexGateway'])->name('admin.pembayaran_gateway.index');
    });

    // Admin & Petugas
    Route::middleware('role:admin|petugas')->group(function () {
        Route::resource('admin/pelanggan', PelangganController::class)->names('admin.pelanggan');
        Route::resource('petugas/pemakaian', PemakaianAirController::class)->names('petugas.pemakaian');
        Route::get('/api/pelanggan/{id}/last-meter', [PemakaianAirController::class, 'getLastMeter'])->name('api.pelanggan.lastMeter');
        Route::get('/petugas/tagihan', [TagihanController::class, 'index'])->name('petugas.tagihan');
        Route::get('/admin/pengaduan', [PengaduanController::class, 'index'])->name('admin.pengaduan');
        Route::get('/admin/pengaduan/{id}', [PengaduanController::class, 'show'])->name('admin.pengaduan.show');
        Route::post('/admin/pengaduan/{id}', [PengaduanController::class, 'respon'])->name('admin.pengaduan.respon');
    });

    // Petugas Only
    Route::middleware('role:petugas')->group(function () {
        Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard');
        Route::get('/petugas/pembayaran/{id}', [PembayaranController::class, 'loket'])->name('petugas.pembayaran');
        Route::post('/petugas/pembayaran/{id}', [PembayaranController::class, 'bayarLoket'])->name('petugas.pembayaran.bayar');
        Route::get('/petugas/pembayaran/{id}/cetak', [PembayaranController::class, 'cetak'])->name('petugas.pembayaran.cetak');
    });

    // Pelanggan Only
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/pelanggan/dashboard', [DashboardController::class, 'pelanggan'])->name('pelanggan.dashboard');
        Route::get('/pelanggan/tagihan', [TagihanController::class, 'pelanggan'])->name('pelanggan.tagihan');
        Route::get('/pelanggan/bayar/{id}', [PembayaranController::class, 'online'])->name('pelanggan.pembayaran');
        Route::post('/pelanggan/bayar/{id}', [PembayaranController::class, 'bayarOnline'])->name('pelanggan.pembayaran.bayar');
        Route::get('/pelanggan/riwayat', [PembayaranController::class, 'riwayat'])->name('pelanggan.riwayat');
        Route::resource('pelanggan/pengaduan', PengaduanController::class)->names([
            'index' => 'pelanggan.pengaduan.index',
            'create' => 'pelanggan.pengaduan.create',
            'store' => 'pelanggan.pengaduan.store',
            'show' => 'pelanggan.pengaduan.show',
        ]);
    });

    // Pimpinan
    Route::middleware('role:pimpinan')->group(function () {
        Route::get('/pimpinan/dashboard', [DashboardController::class, 'pimpinan'])->name('pimpinan.dashboard');
    });

    // Admin & Pimpinan (Laporan)
    Route::middleware('role:admin|pimpinan')->group(function () {
        Route::get('/admin/laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
        Route::get('/admin/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
        Route::get('/admin/laporan/pemakaian', [LaporanController::class, 'pemakaian'])->name('laporan.pemakaian');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/api/payment/callback', [PaymentCallbackController::class, 'handle'])->name('payment.callback');


require __DIR__.'/auth.php';

