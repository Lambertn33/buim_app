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
        Schema::create('warehouse_device_requested_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id');
            $table->uuid('warehouse_device_request_id');
            $table->string('screener_code');
            $table->string('device_name');
            $table->bigInteger('device_price')->nullable();
            $table->bigInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_device_requested_devices');
    }
};
