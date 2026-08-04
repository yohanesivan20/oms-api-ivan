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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('transaction_reference', 100)->index();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->tinyInteger('status')->index();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
