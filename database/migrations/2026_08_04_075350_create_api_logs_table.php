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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service_name', 100)->index();
            $table->string('endpoint');
            $table->string('method', 20);
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->integer('status_code')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->boolean('is_success')->default(true);
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
