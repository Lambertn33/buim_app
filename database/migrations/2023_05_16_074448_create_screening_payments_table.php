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
        Schema::create('screening_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('screener_id');
            $table->uuid('payment_plan_id');
            $table->bigInteger('amount_paid');
            $table->bigInteger('remaining_amount');
            $table->date('next_payment_date');
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
