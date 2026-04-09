<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = env('IPAYMU_API_KEY');
$va = env('IPAYMU_VA');
$url = env('IPAYMU_URL');

echo "Testing iPaymu with:\n";
echo "VA: $va\n";
echo "URL: $url\n\n";

$body = [
    'name' => 'Budi Pelanggan',
    'phone' => '0812345678',
    'email' => 'pelanggan@pdam.com',
    'amount' => '352500',
    'paymentMethod' => 'va',
    'paymentChannel' => 'cimb',
];

ksort($body);
$jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
$requestHash = strtolower(hash('sha256', $jsonBody));
$stringToSign = 'POST:' . $va . ':' . $requestHash . ':' . $apiKey;
$signature = hash_hmac('sha256', $stringToSign, $apiKey);
$timestamp = date('YmdHis');

$headers = [
    'Content-Type' => 'application/json',
    'signature' => $signature,
    'va' => $va,
    'timestamp' => $timestamp,
];

echo "Sending request to iPaymu...\n";
$ch = curl_init($url . '/payment/direct');
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function($v, $k) { return "$k: $v"; }, $headers, array_keys($headers)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
