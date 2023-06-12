<?php

use App\Models\ScreeningPayment;
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
        Schema::create('screening_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('screener_id');
            $table->bigInteger('amount');
            $table->enum('payment_type', ScreeningPayment::PAYMENT_TYPE);
            $table->enum('payment_mode', ScreeningPayment::PAYMENT_MODE);
            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_payments');
    }
};
