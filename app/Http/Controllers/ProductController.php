<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use Dedoc\Scramble\Attributes\Group;

#[Group('Products')]
class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    /**
     * Get Product List
     *
     * Returns all products from DummyJSON.
     */
    public function index()
    {
        $products = $this->productService->getProducts();

        return $this->success($products, 'Products retrieved successfully.');
    }

    /**
     * Product Detail
     *
     * Returns a single product by id.
     */
    public function show(int $id)
    {
        $product = $this->productService->getProduct($id);

        if ($product === null) {
            return $this->error(
                'Product not found.',
                404
            );
        }

        return $this->success(
            $product,
            'Product retrieved successfully.'
        );
    }
}
