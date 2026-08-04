<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentWebhookRequest;
use App\Services\PaymentService;

use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;

#[Group('Webhook Payments')]
class WebhookController extends Controller
{
    //
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function payment(
        PaymentWebhookRequest $request
    ) {
        try {
            $payment = $this->paymentService
                ->webhook(
                    $request->validated()
                );

            return $this->success(
                $payment,
                'Webhook processed.'
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
