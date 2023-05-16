<?php

use App\Models\ScreeningInstallation;
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
        Schema::create('screening_installations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('screener_id');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('installation_status', ScreeningInstallation::INSTALLATION_STATUS)->default(ScreeningInstallation::INSTALLATION_PENDING);
            $table->enum('verification_status', ScreeningInstallation::VERIFICATION_STATUS)->default(ScreeningInstallation::VERIFICATION_PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_installations');
    }
};
