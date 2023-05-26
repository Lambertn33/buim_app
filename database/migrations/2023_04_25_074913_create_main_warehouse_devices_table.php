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
        Schema::create('main_warehouse_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('model_id');
            $table->uuid('main_warehouse_id');
            $table->string('device_name');
            $table->bigInteger('device_price')->nullable();
            $table->string('initialization_code');
            $table->boolean('is_approved');
            $table->uuid('initialized_by');
            $table->uuid('approved_by')->nullable();
            $table->string('serial_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_warehouse_devices');
    }
};
