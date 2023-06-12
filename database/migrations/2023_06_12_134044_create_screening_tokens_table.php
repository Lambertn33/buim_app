<?php

use App\Models\ScreeningToken;
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
        Schema::create('screening_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('screening_payment_id');
            $table->string('token');
            $table->integer('validity_days');
            $table->string('key');
            $table->enum('status', ScreeningToken::STATUS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_tokens');
    }
};
