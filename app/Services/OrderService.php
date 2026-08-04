<?php

namespace App\Services;

use App\Jobs\ProcessPaymentJob;
use App\Jobs\SendOrderCreatedEmailJob;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected ProductService $productService,
    ) {}

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $productIds = collect($data['items'])
                ->pluck('product_id')
                ->unique()
                ->values()
                ->toArray();

            $products = $this->productService->getProductsByIds($productIds);

            $subtotal = 0;
            $orderItems = [];

            foreach ($data['items'] as $item) {

                if (!isset($products[$item['product_id']])) {
                    throw new \Exception(
                        "Product {$item['product_id']} not found."
                    );
                }

                $product = $products[$item['product_id']];

                $itemSubtotal = $product['price'] * $item['quantity'];

                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'external_product_id' => $product['id'],
                    'product_name'        => $product['title'],
                    'product_price'       => $product['price'],
                    'quantity'            => $item['quantity'],
                    'subtotal'            => $itemSubtotal,
                    'product_snapshot'    => $product,
                ];
            }

            $shippingCost = 0;

            $order = $this->orderRepository->createOrder([
                'order_number'    => $this->generateOrderNumber(),
                'customer_name'   => $data['customer_name'],
                'customer_email'  => $data['customer_email'],
                'status'          => OrderStatus::PENDING,
                'payment_status'  => PaymentStatus::UNPAID,
                'shipping_status' => ShippingStatus::PENDING,
                'subtotal'        => $subtotal,
                'shipping_cost'   => $shippingCost,
                'grand_total'     => $subtotal + $shippingCost,
                'currency'        => config('order.currency'),
                'notes'           => $data['notes'] ?? null,
            ]);

            foreach ($orderItems as &$orderItem) {
                $orderItem['order_id'] = $order->id;
            }

            $this->orderRepository->createOrderItems(
                $order,
                $orderItems
            );

            ProcessPaymentJob::dispatch($order->id)->afterCommit();
            SendOrderCreatedEmailJob::dispatch($order->id)->afterCommit();

            return $this->orderRepository->findById($order->id);
        });
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }
}