<?php

namespace App\Jobs;

use Throwable;
use App\Models\Order;
use App\Mail\OrderCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $orderId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        Mail::to($order->customer_email)
            ->send(new OrderCreatedMail($order));
    }

    public function failed(Throwable $exception): void
    {
        \Log::error('SendOrderCreatedEmailJob Failed', [
            'order_id' => $this->orderId,
            'message' => $exception->getMessage(),
        ]);
    }
}
