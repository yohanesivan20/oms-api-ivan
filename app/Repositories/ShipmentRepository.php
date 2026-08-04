<?php

namespace App\Repositories;

use App\Models\Shipment;

class ShipmentRepository
{
    public function create(array $data): Shipment
    {
        return Shipment::create($data);
    }

    public function findByTrackingNumber(string $trackingNumber): ?Shipment
    {
        return Shipment::where('tracking_number', $trackingNumber)->first();
    }
    
    public function findByOrderId(int $orderId): ?Shipment
    {
        return Shipment::where('order_id', $orderId)->first();
    }

    public function findById(int $id): ?Shipment
    {
        return Shipment::find($id);
    }

    public function update(Shipment $shipment, array $data): bool
    {
        return $shipment->update($data);
    }
}