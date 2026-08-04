<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Services\ShipmentService;

use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Shipments')]
class ShipmentController extends Controller
{
    //
    public function __construct(protected ShipmentService $shipmentService) {}

    public function store(
        StoreShipmentRequest $request,
        int $order
    ) {
        try {
            $shipment = $this->shipmentService->create(
                $order,
                $request->validated()
            );

            return $this->success(
                $shipment,
                'Shipment created successfully.',
                201
            );
        } catch (\RuntimeException $e) {
            return $this->error(
                $e->getMessage(),
                404
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Internal Server Error',
                500
            );
        }
    }
}
