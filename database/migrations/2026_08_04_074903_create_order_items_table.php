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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('external_product_id', 50);
            $table->string('product_name');
            $table->decimal('product_price', 12, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->json('product_snapshot');
            
            $table->timestamps();

            $table->index('external_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
