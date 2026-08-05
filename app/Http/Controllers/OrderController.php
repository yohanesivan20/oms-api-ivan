<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Validation\ValidationException;

#[Group('Orders')]
class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    /**
     * Create Order
     *
     * Create new order and dispatch payment & email queue.
     */
    public function store(StoreOrderRequest $request)
    {
        try {

            $order = $this->orderService->store(
                $request->validated()
            );

            return $this->success(
                $order,
                'Order created successfully.',
                201
            );

        } catch (\RuntimeException $e) {
            return $this->error(
                $e->getMessage(),
                404
            );
        } catch (Throwable $e) {
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
}
