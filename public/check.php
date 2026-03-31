<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pelanggan;
use App\Models\GolonganTarif;
use App\Models\User;

// Force creation
$gol = GolonganTarif::updateOrCreate(['kode_golongan'=>'R1'],['nama_golongan'=>'Rumah Tangga 1','tarif_per_m3'=>3500,'biaya_admin'=>2500]);
$user = User::firstOrCreate(['username'=>'budi_baru'],['name'=>'Budi Baru','password'=>bcrypt('password'),'role'=>'pelanggan','is_active'=>1]);
$pel = Pelanggan::updateOrCreate(['user_id'=>$user->id],['nomor_pelanggan'=>'PLG0011','nama'=>'Budi Baru','alamat'=>'Jl. Merdeka','nomor_meter'=>'MTR088','golongan_tarif_id'=>$gol->id,'is_active'=>1]);

echo "CREATED ID: " . $pel->id . " | NO: " . $pel->nomor_pelanggan . "\n";
echo "PEL_COUNT: " . Pelanggan::count() . "\n";
foreach (Pelanggan::all() as $p) {
    echo "ID: {$p->id} | NO: {$p->nomor_pelanggan} | NAME: {$p->nama} | IS_ACTIVE: {$p->is_active}\n";
}
