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
        Schema::create('captions', function (Blueprint $table) {
            $table->id();
            $table->string('caption', 300);
            $table->enum('lang', ['en', 'ar']);
            $table->foreignId('service_id');
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('captions');
    }
};
