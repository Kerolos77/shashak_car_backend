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
        Schema::create('driver_licenses', function (Blueprint $table) {
            $table->id();

            $table->string('front_license_image');
            $table->string('back_license_image');
            $table->string('driver_with_license_image');

            $table->date('expiry_date');
            $table->enum('status', ['active', 'expired'])->default('active');


            $table->foreignId('driver_profile_id');
            $table->foreign('driver_profile_id')->references('id')->on('driver_profiles')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_licenses');
    }
};
