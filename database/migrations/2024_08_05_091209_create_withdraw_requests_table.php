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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['success','failed','pending','rejected'])->default('pending');
            $table->enum('type', ['withdraw'])->default('withdraw');
            $table->boolean('success')->default(0);
            $table->string('amount')->default(0);
            $table->string('payroll_method')->default('card');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('withdraw_requests');
    }
};
