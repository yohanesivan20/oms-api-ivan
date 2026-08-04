<?php

namespace App\Jobs;

use Throwable;
use App\Services\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId){}

    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService): void
    {
        $paymentService->create($this->orderId);
    }

    public function failed(Throwable $exception): void
    {
        \Log::error('ProcessPaymentJob Failed', [
            'order_id' => $this->orderId,
            'message' => $exception->getMessage(),
        ]);
    }
}
