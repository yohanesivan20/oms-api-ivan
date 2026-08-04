<?php

namespace App\Services;

use App\Clients\ShipmentClient;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Repositories\OrderRepository;
use App\Repositories\ShipmentRepository;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ShipmentService
{
    public function __construct(
        protected ShipmentRepository $shipmentRepository,
        protected OrderRepository $orderRepository,
        protected ShipmentClient $shipmentClient
    ) {}

    public function create(int $orderId, array $payload)
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \RuntimeException('Order not found.');
        }

        if ($order->payment_status->value != PaymentStatus::PAID->value) {
            throw new \RuntimeException('Order has not been paid.');
        }

        if ($this->shipmentRepository->findByOrderId($orderId)) {
            throw new \RuntimeException('Shipment already exists.');
        }

        return DB::transaction(function () use ($order, $payload) {

            $shipping = $this->shipmentClient->calculateCost([
                'origin' => $payload['origin'],
                'destination' => $payload['destination'],
                'weight' => $payload['weight'],
                'courier' => $payload['courier'],
            ]);

            if (
                !isset($shipping['data']) ||
                empty($shipping['data'])
            ) {
                throw new RuntimeException(
                    'Shipping cost not found.'
                );
            }

            $service = $shipping['data'][0];

            $shipment = $this->shipmentRepository->create([
                'order_id' => $order->id,
                'courier' => strtoupper($payload['courier']),
                'service' => $service['service'],
                'shipping_cost' => $service['cost'],
                'tracking_number' => $this->generateTrackingNumber(
                    $payload['courier']
                ),
                'estimated_delivery' => $service['etd'],
                'status' => ShippingStatus::PENDING->value,
                'response' => $shipping,
            ]);

            $this->orderRepository->update($order, [
                'shipping_status' => ShippingStatus::PENDING->value,
                'status' => OrderStatus::PROCESSING->value,
            ]);

            return $shipment;
        });
    }

    private function generateTrackingNumber(
        string $courier
    ): string {

        return strtoupper($courier)
            . now()->format('ymdHis')
            . random_int(1000, 9999);
    }
}