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
        Schema::create('driver_cars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_profile_id');
            $table->foreign('driver_profile_id')->references('id')->on('driver_profiles')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('car_brand_id');
            $table->foreign('car_brand_id')->references('id')->on('car_brands')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('car_model_id');
            $table->foreign('car_model_id')->references('id')->on('car_models')->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('color', 15);
            
            $table->year('release_year');

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_cars');
    }
};
