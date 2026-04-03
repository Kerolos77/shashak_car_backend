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
        Schema::create('order_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->string('car_color');
            $table->string('car_number');
            $table->string('car_brand');
            $table->string('car_model');

            $table->foreignId('driver_id');
            $table->foreign('driver_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_offers');
    }
};
