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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_reference', 100)->unique();
            $table->string('provider', 100);
            $table->decimal('amount', 12, 2);
            $table->text('payment_url')->nullable();
            $table->tinyInteger('status')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
