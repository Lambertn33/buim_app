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
            $table->uuid('model_id');
            $table->uuid('campaign_id');
            $table->bigInteger('quantity');
            $table->enum('status', WarehouseDeviceRequest::STATUS)->default(WarehouseDeviceRequest::REQUESTED);
            $table->string('denied_note')->nullable();
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
