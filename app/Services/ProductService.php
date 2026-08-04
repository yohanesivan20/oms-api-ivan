<?php

namespace App\Services;

use App\Clients\ProductClient;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ProductService
{
    public function __construct(protected ProductClient $productClient) {}

    public function getProducts(): array
    {
        return Cache::remember(
            'products:list',
            now()->addMinutes(10),
            function () {
                return $this->productClient->getProducts();
            }
        );
    }

    public function getProduct(int $id): ?array
    {
        $cacheKey = "products:{$id}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $product = $this->productClient->getProduct($id);

        if ($product !== null) {
            Cache::put(
                $cacheKey,
                $product,
                now()->addMinutes(10)
            );
        }

        return $product;
    }

    public function getProductsByIds(array $productIds): array
    {
        $products = [];

        foreach ($productIds as $productId) {
            $products[$productId] = $this->getProduct($productId);
        }

        return $products;
    }
}