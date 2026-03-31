<?php

$va = '0000002284733404';
$apiKey = 'SANDBOXCDCE391E-C4B7-4E55-B4B5-B05C92CC11A9-20211029145207';
$url = 'https://sandbox.ipaymu.com/api/v2/payment/direct';

$body = [
    'name'           => 'Budi Pelanggan',
    'phone'          => '081234721',
    'email'          => 'pelanggan@pdam.com',
    'amount'         => 10000,
    'notifyUrl'      => 'http://139.162.5.182:8088/api/ipaymu/callback',
    'expired'        => 24,
    'referenceId'    => 'TEST-123',
    'paymentMethod'  => 'va',
    'paymentChannel' => 'bri',
];

ksort($body);
$jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
$hash = strtolower(hash('sha256', $jsonBody));
$stringToSign = "POST:$va:$hash:$apiKey";
$signature = hash_hmac('sha256', $stringToSign, $apiKey);
$timestamp = date('YmdHis');

echo "CURL COMMAND:\n";
echo "curl -X POST $url \\\n";
echo "     -H 'Accept: application/json' \\\n";
echo "     -H 'Content-Type: application/json' \\\n";
echo "     -H 'va: $va' \\\n";
echo "     -H 'signature: $signature' \\\n";
echo "     -H 'timestamp: $timestamp' \\\n";
echo "     -d '$jsonBody'\n";
