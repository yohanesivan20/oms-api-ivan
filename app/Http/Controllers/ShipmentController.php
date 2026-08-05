<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Services\ShipmentService;

use Throwable;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        } catch (Throwable $e) {dd($e);
            return $this->error(
                'Internal Server Error',
                500
            );
        } catch (ValidationException $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        }
    }

    public function searchDestination(Request $request)
    {
        try {
            $validated = $request->validate([
                'keyword' => ['required', 'string', 'min:2'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            ]);

            $result = $this->shipmentService->searchDestination(
                $validated['keyword'],
                $validated['limit'] ?? 10
            ); 

            return $this->success(
                $result,
                'Destination retrieved successfully.'
            );
        } catch (\RuntimeException $e) {
            return $this->error(
                $e->getMessage(),
                404
            );
        } catch (ValidationException $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        } catch (Throwable $e) {
            return $this->error(
                'Internal Server Error',
                500
            );
        }
    }

    public function calculateCost(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin' => ['required', 'string'],
                'destination' => ['required', 'string'],
                'weight' => ['required', 'integer', 'min:1'],
                'courier' => [
                    'required',
                    Rule::in([
                        'jne',
                        'jnt',
                        'sicepat',
                        'anteraja',
                        'pos',
                        'tiki',
                    ]),
                ],
            ]);

            $result = $this->shipmentService->calculateCost($validated);

            return $this->success(
                $result['data'] ?? $result,
                'Shipping cost calculated successfully.'
            );
        } catch (\RuntimeException $e) {
            return $this->error(
                $e->getMessage(),
                404
            );
        } catch (ValidationException $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        } catch (Throwable $e) {
            return $this->error(
                'Internal Server Error',
                500
            );
        }
        catch (ValidationException $e) {
            return $this->error(
                $e->getMessage(),
                422
            );
        } catch (Throwable $e) {
            dd($e);
            return $this->error(
                'Internal Server Error',
                500
            );
        }
    }
}
