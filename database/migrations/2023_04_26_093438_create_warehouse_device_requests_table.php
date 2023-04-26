<?php

use App\Models\WarehouseDeviceRequest;
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
        Schema::create('warehouse_device_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id');
            $table->bigInteger('request_id');
            $table->enum('request_status', WarehouseDeviceRequest::REQUEST_STATUS)->default(WarehouseDeviceRequest::INITIATED);
            $table->enum('confirmation_status', WarehouseDeviceRequest::CONFIRMATION_STATUS)->default(WarehouseDeviceRequest::PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_device_requests');
    }
};
