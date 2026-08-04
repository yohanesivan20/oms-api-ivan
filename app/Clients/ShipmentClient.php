<?php

namespace App\Clients;

use App\Services\ApiLogService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Throwable;

class ShipmentClient
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct(
        protected ApiLogService $apiLogService
    ) {
        $this->baseUrl = config('services.shipment.url');
        $this->apiKey = config('services.shipment.api_key');
    }

    protected function client()
    {
        return Http::asForm()
        ->baseUrl($this->baseUrl)
        ->acceptJson()
        ->timeout(20)
        ->retry(2, 500)
        ->withHeaders([
            'key' => $this->apiKey,
        ]);
    }

    public function searchDestination(
        string $keyword,
        int $limit = 10
    ): array {

        try {

            $response = $this->client()
                ->get('/destination/domestic-destination', [
                    'search' => $keyword,
                    'limit' => $limit,
                ]);

            $response->throw();

            $this->apiLogService->store([
                'service_name' => 'RajaOngkir',
                'endpoint' => '/destination/domestic-destination',
                'method' => 'GET',
                'request' => [
                    'search' => $keyword,
                    'limit' => $limit,
                ],
                'response' => $response->json(),
                'status_code' => $response->status(),
                'is_success' => true,
            ]);

            return $response->json();

        } catch (RequestException $e) {

            $this->apiLogService->store([
                'service_name' => 'RajaOngkir',
                'endpoint' => '/calculate/domestic-cost',
                'method' => 'POST',
                'request' => $payload,
                'response' => $e->response?->json(),
                'status_code' => $e->response?->status() ?? 500,
                'is_success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;

        }catch (Throwable $e) {

            $this->apiLogService->store([
                'service_name' => 'RajaOngkir',
                'endpoint' => '/destination/domestic-destination',
                'method' => 'GET',
                'request' => [
                    'search' => $keyword,
                    'limit' => $limit,
                ],
                'status_code' => method_exists($e, 'getCode')
                    ? $e->getCode()
                    : 500,
                'is_success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function calculateCost(array $payload): array
    {
        try {

            $response = Http::asForm()
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 500)
            ->withHeaders([
                'key' => $this->apiKey,
            ])
            ->post('/calculate/domestic-cost', [
                'origin' => $payload['origin'],
                'destination' => $payload['destination'],
                'weight' => $payload['weight'],
                'courier' => $payload['courier'],
                'price' => 'lowest',
            ]);

            $response->throw();

            $this->apiLogService->store([
                'service_name' => 'RajaOngkir',
                'endpoint' => '/calculate/domestic-cost',
                'method' => 'POST',
                'request' => $payload,
                'response' => $response->json(),
                'status_code' => $response->status(),
                'is_success' => true,
            ]);

            return $response->json();

        } catch (Throwable $e) {

            $this->apiLogService->store([
                'service_name' => 'RajaOngkir',
                'endpoint' => '/calculate/domestic-cost',
                'method' => 'POST',
                'request' => $payload,
                'status_code' => method_exists($e, 'getCode')
                    ? $e->getCode()
                    : 500,
                'is_success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}