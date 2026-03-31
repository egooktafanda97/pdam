<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpaymuService
{
    protected string $apiKey;
    protected string $va;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('ipaymu.api_key');
        $this->va = config('ipaymu.va');
        $this->baseUrl = config('ipaymu.url');
    }

    /**
     * Generate iPaymu signature
     * Format: HMAC-SHA256(POST:VA:SHA256(body):ApiKey, ApiKey)
     */
    protected function generateSignature(string $jsonBody): string
    {
        $requestHash = strtolower(hash('sha256', $jsonBody));
        $stringToSign = 'POST:' . $this->va . ':' . $requestHash . ':' . $this->apiKey;
        return hash_hmac('sha256', $stringToSign, $this->apiKey);
    }

    /**
     * Create a direct payment (Virtual Account / QRIS)
     *
     * @param array $params [name, phone, email, amount, paymentMethod, paymentChannel, referenceId]
     * @return array ['success' => bool, 'data' => [...]]
     */
    public function createDirectPayment(array $params): array
    {
        $body = [
            'name' => $params['name'],
            'phone' => $params['phone'] ?? '081200000000',
            'email' => $params['email'] ?? 'pelanggan@pdam.com',
            'amount' => (int) $params['amount'],
            'notifyUrl' => $params['notifyUrl'] ?? url('/api/ipaymu/callback'),
            'expired' => $params['expired'] ?? 24,
            'referenceId' => $params['referenceId'] ?? null,
            'paymentMethod' => $params['paymentMethod'], // 'va' or 'qris'
            'paymentChannel' => $params['paymentChannel'], // 'bni', 'bri', 'mandiri', etc.
        ];

        $jsonBody = json_encode($body);
        $signature = $this->generateSignature($jsonBody);
        $timestamp = date('YmdHis');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature' => $signature,
                'va' => $this->va,
                'timestamp' => $timestamp,
            ])->withBody($jsonBody, 'application/json')
              ->post($this->baseUrl . '/payment/direct');

            $result = $response->json();

            Log::info('iPaymu Response', ['status' => $response->status(), 'body' => $result]);

            if ($response->successful() && isset($result['Data'])) {
                return [
                    'success' => true,
                    'transaction_id' => $result['Data']['TransactionId'] ?? null,
                    'session_id' => $result['Data']['SessionId'] ?? null,
                    'payment_no' => $result['Data']['PaymentNo'] ?? null,
                    'payment_name' => $result['Data']['PaymentName'] ?? null,
                    'expired' => $result['Data']['Expired'] ?? null,
                    'qr_url' => $result['Data']['QrUrl'] ?? null,
                    'raw' => $result,
                ];
            }

            return [
                'success' => false,
                'message' => $result['Message'] ?? 'Gagal membuat pembayaran di iPaymu',
                'raw' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('iPaymu Error', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Gagal terhubung ke iPaymu: ' . $e->getMessage(),
            ];
        }
    }
}
