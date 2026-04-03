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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('from_user_id')->nullable();
            $table->foreign('from_user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('to_user_id')->nullable();
            $table->foreign('to_user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();

            $table->enum('reviewer', ['user', 'driver'])->nullable();

            $table->text('comment')->nullable();

            $table->boolean('rating')->nullable();
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
