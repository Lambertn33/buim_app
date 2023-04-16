<?php

use App\Models\SubStockRequest;
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
        Schema::create('sub_stock_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('request_id')->unique();
            $table->uuid('campaign_id');
            $table->enum('request_status', SubStockRequest::STOCKREQUESTSTATUS)->default(SubStockRequest::REQUESTED);
            $table->enum('confirmation_status', SubStockRequest::STOCKCONFIRMATIONSTATUS)->default(SubStockRequest::PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_stock_requests');
    }
};
