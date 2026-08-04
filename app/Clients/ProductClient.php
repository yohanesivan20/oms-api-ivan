<?php

namespace App\Clients;

use Throwable;
use App\Services\ApiLogService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ProductClient
{
    protected string $baseUrl;

    public function __construct(protected ApiLogService $apiLogService) 
    {
        $this->baseUrl = config('services.dummyjson.url');
    }

    public function getProducts(array $query = []): array
    {
        try {
            $response = Http::baseUrl($this->baseUrl)
                ->acceptJson()
                ->timeout(10)
                ->retry(2, 500)
                ->get('/products', $query);

            $response->throw();

            $this->apiLogService->store([
                'service_name' => 'DummyJSON',
                'endpoint' => '/products',
                'method' => 'GET',
                'response' => $response->json(),
                'status_code' => $response->status(),
                'is_success' => true,
            ]);

            return $response->json();

        } catch (Throwable $e) {

            $this->apiLogService->store([
                'service_name' => 'DummyJSON',
                'endpoint' => '/products',
                'method' => 'GET',
                'status_code' => method_exists($e, 'getCode') ? $e->getCode() : 500,
                'is_success' => false,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function getProduct(int $id): ?array
    {
        try {
            return Http::baseUrl(config('services.dummyjson.url'))
                ->acceptJson()
                ->timeout(10)
                ->retry(2, 500)
                ->get("/products/{$id}")
                ->throw()
                ->json();
        } catch (RequestException $e) {

            if ($e->response && $e->response->status() === 404) {
                return null;
            }

            throw $e;
        }
    }
}