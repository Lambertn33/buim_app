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
        Schema::create('stock_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('screener_id')->nullable();
            $table->uuid('model_id');
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_devices');
    }
};