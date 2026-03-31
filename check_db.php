<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pelanggan;
use App\Models\GolonganTarif;
use App\Models\User;

$report = "--- DATABASE REPORT ---\n";
$report .= "Date: " . date('Y-m-d H:i:s') . "\n";
$report .= "Users (role=pelanggan): " . User::where('role', 'pelanggan')->count() . "\n";
$report .= "Pelanggan Records: " . Pelanggan::count() . "\n";
$report .= "GolonganTarif Records: " . GolonganTarif::count() . "\n";

if (Pelanggan::count() > 0) {
    $report .= "\nPelanggan List:\n";
    foreach (Pelanggan::all() as $p) {
        $report .= "- ID: {$p->id}, Name: {$p->nama}, Active: {$p->is_active}, UserID: {$p->user_id}\n";
    }
}

file_put_contents('db_report.txt', $report);
echo "Report written to db_report.txt\n";
