<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 100)->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->tinyInteger('status')->index();
            $table->tinyInteger('payment_status')->index();
            $table->tinyInteger('shipping_status')->index();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->string('currency', 10)->default('IDR');
            $table->string('idempotency_key', 100)->unique();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'status',
                'payment_status',
                'shipping_status'
            ], 'orders_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
