<?php

use App\Models\WarehouseDeviceTransfer;
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
        Schema::create('warehouse_device_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_sender_id');
            $table->uuid('warehouse_receiver_id');
            $table->uuid('manager_sender_id');
            $table->uuid('manager_receiver_id');
            $table->string('serial_number');
            $table->string('device_name');
            $table->string('description');
            $table->enum('status', WarehouseDeviceTransfer::STATUS)->default(WarehouseDeviceTransfer::PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_device_transfers');
    }
};
