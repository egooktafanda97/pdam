<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::where('role', '!=', 'pelanggan')->get();
echo "Total internal users: " . $users->count() . "\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Username: {$user->username} | Role: {$user->role}\n";
}
