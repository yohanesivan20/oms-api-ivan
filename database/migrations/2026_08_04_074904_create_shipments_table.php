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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('courier', 100);
            $table->string('service', 100);
            $table->decimal('shipping_cost', 12, 2);
            $table->string('tracking_number', 100)->nullable()->index();
            $table->string('estimated_delivery', 100)->nullable();
            $table->tinyInteger('status')->index();
            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
