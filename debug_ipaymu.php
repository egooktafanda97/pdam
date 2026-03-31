<?php

// Load project config
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$va = config('ipaymu.va');
$apiKey = config('ipaymu.api_key');
$url = config('ipaymu.url') . '/payment/direct';

if (!$va || !$apiKey) {
    die("Error: IPAYMU_VA or IPAYMU_API_KEY is missing in .env\n");
}

// 1. Prepare Body (same as IpaymuService)
$body = [
    'name'           => 'Budi Pelanggan',
    'phone'          => '081234721',
    'email'          => 'pelanggan@pdam.com',
    'amount'         => 10000,
    'notifyUrl'      => url('/api/ipaymu/callback'),
    'expired'        => 24,
    'referenceId'    => 'TEST-' . time(),
    'paymentMethod'  => 'va',
    'paymentChannel' => 'bri',
];

// Handle same logic as Service: filter null and sort
$body = array_filter($body, fn($v) => !is_null($v));
ksort($body);

$jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
$timestamp = date('YmdHis');

// 2. Generate Signature
$requestHash = strtolower(hash('sha256', $jsonBody));
$stringToSign = "POST:$va:$requestHash:$apiKey";
$signature = hash_hmac('sha256', $stringToSign, $apiKey);

echo "--- Debug iPaymu ---\n";
echo "VA: $va\n";
echo "URL: $url\n";
echo "JSON Body: $jsonBody\n";
echo "StringToSign: POST:$va:$requestHash:" . substr($apiKey, 0, 7) . "...\n";
echo "Signature: $signature\n";
echo "\n--- Copy & Paste Perintah CURL ini ---\n\n";

echo "curl -X POST $url \\\n";
echo "     -H 'Accept: application/json' \\\n";
echo "     -H 'Content-Type: application/json' \\\n";
echo "     -H 'va: $va' \\\n";
echo "     -H 'signature: $signature' \\\n";
echo "     -H 'timestamp: $timestamp' \\\n";
echo "     -d '$jsonBody'\n\n";
