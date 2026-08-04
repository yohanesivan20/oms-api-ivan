<?php

namespace App\Clients;

use Illuminate\Support\Str;

class PaymentClient
{
    public function createPayment(array $payload): array
    {
        $reference = 'PAY-' . strtoupper(Str::random(10));

        return [
            'payment_reference' => $reference,
            'payment_url' => "https://payment.local/pay/{$reference}",
            'provider' => 'FakeGateway',
            'status' => 0,
            'response' => [
                'success' => true,
                'message' => 'Payment created successfully.'
            ]
        ];
    }
}