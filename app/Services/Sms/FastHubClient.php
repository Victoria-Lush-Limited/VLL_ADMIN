<?php

namespace App\Services\Sms;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Str;
use Throwable;

class FastHubClient
{
    private HttpFactory $http;

    public function __construct(HttpFactory $http)
    {
        $this->http = $http;
    }

    /**
     * @param  array<int, array{text: string, msisdn: string, source: string, reference: string}>  $messages
     * @return array<string, mixed>
     */
    public function sendBulk(array $messages): array
    {
        $baseUrl = rtrim((string) config('services.fasthub.base_url'), '/');
        $sendPath = '/'.ltrim((string) config('services.fasthub.send_path', '/api/sms/send'), '/');
        $clientId = (string) config('services.fasthub.client_id');
        $clientSecret = (string) config('services.fasthub.client_secret');

        if ($baseUrl === '' || Str::contains($baseUrl, 'example-gateway.invalid')) {
            return ['ok' => false, 'status' => 500, 'message' => 'FastHub base URL is not configured.', 'body' => null];
        }

        if ($clientId === '' || $clientSecret === '') {
            return ['ok' => false, 'status' => 500, 'message' => 'FastHub credentials are missing.', 'body' => null];
        }

        if ($messages === [] || count($messages) > 50) {
            return ['ok' => false, 'status' => 422, 'message' => 'Messages must contain between 1 and 50 entries.', 'body' => null];
        }

        try {
            $response = $this->http
                ->acceptJson()
                ->asJson()
                ->connectTimeout(10)
                ->timeout(30)
                ->retry(3, 1000)
                ->post($baseUrl.$sendPath, [
                    'auth' => [
                        'clientId' => $clientId,
                        'clientSecret' => $clientSecret,
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                    ],
                    'messages' => $messages,
                ]);

            $body = $response->json();
            $gatewayStatus = is_array($body) ? ($body['status'] ?? null) : null;
            $normalizedStatus = is_string($gatewayStatus) ? strtolower(trim($gatewayStatus)) : $gatewayStatus;
            $hasExplicitFailureStatus = in_array($normalizedStatus, [false, 0, '0', 'false', 'failed', 'error', 'rejected'], true);
            $ok = $response->successful() && ! $hasExplicitFailureStatus;

            $message = is_array($body) ? (string) ($body['message'] ?? 'Gateway request processed.') : 'Gateway request processed.';
            $balance = is_array($body) ? ($body['balance'] ?? null) : null;
            if (is_numeric($balance) && (float) $balance <= 0) {
                $message .= ' FastHub balance is 0. Please top up SMS credits.';
            }

            return [
                'ok' => $ok,
                'status' => $response->status(),
                'message' => $message,
                'body' => $body,
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'status' => 502,
                'message' => 'Failed to connect to FastHub gateway.',
                'body' => ['error' => $exception->getMessage()],
            ];
        }
    }
}
