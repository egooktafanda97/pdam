<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pelanggan;
use App\Models\PemakaianAir;

$bulan = date('n');
$tahun = date('Y');

echo "Debug Info:\n";
echo "Date: " . date('Y-m-d') . " (Month: $bulan, Year: $tahun)\n";
echo "Total Pelanggan: " . Pelanggan::count() . "\n";
echo "Active Pelanggan: " . Pelanggan::where('is_active', true)->count() . "\n";

$pelangganQuery = Pelanggan::whereDoesntHave('pemakaianAir', function ($query) use ($bulan, $tahun) {
    $query->where('periode_bulan', $bulan)->where('periode_tahun', $tahun);
})->where('is_active', true);

echo "Pelanggan without readings this month: " . $pelangganQuery->count() . "\n";

if ($pelangganQuery->count() > 0) {
    echo "Sample Pelanggan:\n";
    foreach ($pelangganQuery->take(5)->get() as $p) {
        echo "- {$p->nomor_pelanggan}: {$p->nama}\n";
    }
} else {
    echo "Reason for 0 results:\n";
    $allActive = Pelanggan::where('is_active', true)->get();
    foreach ($allActive as $p) {
        $hasReading = PemakaianAir::where('pelanggan_id', $p->id)
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->exists();
        if ($hasReading) {
            echo "- {$p->nomor_pelanggan}: Already has reading for $bulan/$tahun\n";
        } else {
            echo "- {$p->nomor_pelanggan}: Should be visible but isn't?\n";
        }
    }
}
