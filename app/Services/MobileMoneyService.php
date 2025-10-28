<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MobileMoneyService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $secretKey;
    protected string $appId;
    protected string $callbackUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = env("MOBILE_MONEY_API_URL");
        $this->apiKey = env('MOBILE_MONEY_API_KEY');
        $this->secretKey = env('MOBILE_MONEY_SECRET_KEY');
        $this->appId = env('MOBILE_MONEY_APP_ID');
        $this->callbackUrl = env('MOBILE_MONEY_CALLBACK_URL');
        $this->timeout = env('MOBILE_MONEY_TIMEOUT');
    }

    /**
     * Générer la signature HMAC SHA256 pour une requête
     */
    protected function generateSignature(array $payload): string
    {
        $jsonPayload = json_encode($payload);
        return hash_hmac('sha256', $jsonPayload, $this->secretKey);
    }

    /**
     * Initier un paiement mobile money
     */
    public function collect(array $data): array
    {
        $payload = [
            'app_id' => $data['app_id'] ?? $this->appId,
            'external_ref' => $data['external_ref'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'payer_phone' => $data['payer_phone'],
            'description' => $data['description'] ?? null,
            'callback_url' => $data['callback_url'] ?? $this->callbackUrl,
        ];

        $signature = $this->generateSignature($payload);

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Signature' => $signature,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/payments/collect', $payload);

            $result = $response->json();

            // Logger la transaction
            Log::info('Mobile Money - Payment Initiated', [
                'external_ref' => $data['external_ref'],
                'amount' => $data['amount'],
                'status_code' => $response->status(),
                'response' => $result,
            ]);

            return [
                'success' => !($result['error'] ?? true),
                'data' => $result,
                'message' => $result['message'] ?? 'Unknown error',
                'status_code' => $response->status(),
                'errors' => $result['errors'] ?? null,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Mobile Money - Connection Error', [
                'external_ref' => $data['external_ref'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Connection error: ' . $e->getMessage(),
                'status_code' => 500,
            ];

        } catch (\Exception $e) {
            Log::error('Mobile Money - Payment Failed', [
                'external_ref' => $data['external_ref'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Error: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function verify(string $transactionId): array
    {
        // Pour une requête GET, on signe un payload vide
        $signature = $this->generateSignature([]);
        
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Signature' => $signature,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl . '/payments/verify/' . $transactionId);

            $result = $response->json();

            Log::info('Mobile Money - Payment Verified', [
                'transaction_id' => $transactionId,
                'status' => $result['status'] ?? 'unknown',
            ]);

            return [
                'success' => !($result['error'] ?? true),
                'data' => $result,
                'message' => $result['message'] ?? 'Transaction retrieved',
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Mobile Money - Verification Failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Error: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }
}
