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
        Schema::create('screenings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id');
            $table->uuid('leader_id');
            $table->date('screening_date');
            $table->string('prospect_names');
            $table->string('prospect_telephone');
            $table->string('prospect_national_id');
            $table->string('prospect_code')->unique();
            $table->string('district');
            $table->string('sector');
            $table->string('cell');
            $table->string('village');
            $table->string('proposed_device_name');
            $table->enum('eligibility_status', \App\Models\Screening::ELIGIBILITY_STATUS);
            $table->enum('confirmation_status', \App\Models\Screening::CONFIRMATION_STATUS);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
