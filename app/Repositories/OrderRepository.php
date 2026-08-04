<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function createOrderItems(Order $order, array $items): void
    {
        $order->items()->createMany($items);
    }

    public function findById(int $id): ?Order
    {
        return Order::with([
            'items',
            'payment',
            'shipment',
        ])->find($id);
    }

    public function update(Order $order, array $data): bool
    {
        return $order->update($data);
    }
}