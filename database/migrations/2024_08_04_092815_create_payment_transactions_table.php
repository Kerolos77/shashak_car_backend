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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->index();
            $table->enum('status', ['success','failed','pending','rejected'])->default('pending');
            $table->enum('type', ['deposit'])->default('deposit');
            $table->boolean('success')->default(0);
            $table->string('amount')->default(0);
            $table->string('payment_method')->default('card');
            $table->string('payment_gateway')->default('paymob');
            $table->foreignId('userID');
            $table->foreign('userID')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
