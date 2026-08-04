<?php

namespace App\Services;

use App\Clients\PaymentClient;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected OrderRepository $orderRepository,
        protected PaymentClient $paymentClient
    ) {}

    public function create(int $orderId)
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \RuntimeException('Order not found.');
        }

        if ($this->paymentRepository->findByOrderId($orderId)) {
            throw new \RuntimeException('Payment already exists.');
        }

        return DB::transaction(function () use ($order) {

            $gateway = $this->paymentClient->createPayment([
                'order_number' => $order->order_number,
                'amount' => $order->grand_total,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
            ]);

            $payment = $this->paymentRepository->create([
                'order_id' => $order->id,
                'payment_reference' => $gateway['payment_reference'],
                'provider' => $gateway['provider'],
                'amount' => $order->grand_total,
                'payment_url' => $gateway['payment_url'],
                'status' => PaymentStatus::UNPAID->value,
                'response' => $gateway['response'],
            ]);

            $this->paymentRepository->createTransaction([
                'payment_id' => $payment->id,
                'transaction_reference' => $gateway['payment_reference'],
                'request_payload' => [
                    'order_number' => $order->order_number,
                    'amount' => $order->grand_total,
                ],
                'response_payload' => $gateway,
                'status' => PaymentStatus::UNPAID->value,
            ]);

            return $payment;
        });
    }

    public function webhook(array $payload)
    {
        return DB::transaction(function () use ($payload) {

            $payment = $this->paymentRepository
                ->findByReference(
                    $payload['payment_reference']
                );

            if (!$payment) {
                throw new \RuntimeException(
                    'Payment not found.'
                );
            }

            $status = strtoupper($payload['status']);

            switch ($status) {

                case 'PAID':

                    $this->paymentRepository->update(
                        $payment,
                        [
                            'status' => PaymentStatus::PAID->value,
                            'paid_at' => now(),
                        ]
                    );

                    $this->orderRepository->update(
                        $payment->order,
                        [
                            'payment_status' => PaymentStatus::PAID->value,
                            'status' => OrderStatus::PROCESSING->value,
                        ]
                    );

                    break;

                case 'FAILED':

                    $this->paymentRepository->update(
                        $payment,
                        [
                            'status' => PaymentStatus::FAILED->value,
                        ]
                    );

                    break;

                case 'EXPIRED':

                    $this->paymentRepository->update(
                        $payment,
                        [
                            'status' => PaymentStatus::EXPIRED->value,
                        ]
                    );

                    break;
            }

            $payment->refresh();
            $this->paymentRepository->createTransaction([
                'payment_id' => $payment->id,
                'transaction_reference' => $payment->payment_reference,
                'request_payload' => $payload,
                'response_payload' => $payload,
                'status' => $payment->status,
            ]);

            return $payment->fresh();
        });
    }
}