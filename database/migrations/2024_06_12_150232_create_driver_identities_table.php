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
        Schema::create('driver_identities', function (Blueprint $table) {
            $table->id();
            // Driver ID Number
            $table->string('id_number', 50);
            // Front Driver Identity Image
            $table->string('front_identity_image', 255);
            // Back Driver Identity Image
            $table->string('back_identity_image', 255);
            // Driver Identity Expiry Date
            $table->date('expiry_date');
            // driver image with his identity
            $table->string('driver_image_with_id', 255);
            // Driver Identity Status
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
        Schema::dropIfExists('driver_identities');
    }
};
