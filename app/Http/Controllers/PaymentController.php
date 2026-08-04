<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Dedoc\Scramble\Attributes\Group;

#[Group('Payments')]
class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * Create Payment
     *
     * Create a new payment for the specified order.
     */
    public function store(int $order)
    {
        try {

            $payment = $this->paymentService->create($order);

            return $this->success(
                $payment,
                'Payment created successfully.',
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