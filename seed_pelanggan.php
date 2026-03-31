<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('username', 'pelanggan1')->first();
$golongan = App\Models\GolonganTarif::first();

if ($user && $golongan) {
    App\Models\Pelanggan::updateOrCreate(
        ['user_id' => $user->id],
        [
            'golongan_tarif_id' => $golongan->id,
            'nomor_pelanggan' => 'PLG2026112',
            'nama' => 'Pelanggan Ujian',
            'alamat' => 'Alamat Pengujian',
            'nomor_meter' => 'MTR-001',
            'no_telepon' => '08123456789'
        ]
    );
    echo "Pelanggan created!\n";
} else {
    echo "User or Golongan not found.\n";
}
