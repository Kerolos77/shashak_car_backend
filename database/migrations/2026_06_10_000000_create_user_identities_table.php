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
        Schema::create('user_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('id_number', 50)->nullable();
            $table->string('front_image', 255)->nullable();
            $table->string('back_image', 255)->nullable();
            $table->string('selfie_image', 255)->nullable();
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->integer('ai_face_similarity')->nullable();
            $table->text('ai_rejection_reason')->nullable();
            $table->json('ai_verification_report')->nullable();
            $table->json('ai_raw_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_identities');
    }
};
