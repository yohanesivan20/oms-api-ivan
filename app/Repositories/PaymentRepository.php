<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;

class PaymentRepository
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function createTransaction(array $data): PaymentTransaction
    {
        return PaymentTransaction::create($data);
    }

    public function findByOrderId(int $orderId): ?Payment
    {
        return Payment::where('order_id', $orderId)->first();
    }

    public function findByReference(string $reference): ?Payment
    {
        return Payment::with('order')
            ->where('payment_reference', $reference)
            ->first();
    }

    public function update(Payment $payment, array $data): bool
    {
        return $payment->update($data);
    }
}