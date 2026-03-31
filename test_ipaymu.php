<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Services\IpaymuService;

$ipaymu = new IpaymuService();
$params = [
    'name'           => 'Budi Pelanggan',
    'phone'          => '081234721',
    'email'          => 'pelanggan@pdam.com',
    'amount'         => 352500,
    'paymentMethod'  => 'va',
    'paymentChannel' => 'bri',
    'referenceId'    => 'TEST-' . time(),
];

echo "Testing IpaymuService Direct Payment...\n";
$result = $ipaymu->createDirectPayment($params);

echo "Result:\n";
print_r($result);
