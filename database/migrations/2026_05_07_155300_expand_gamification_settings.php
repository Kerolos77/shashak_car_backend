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
        Schema::table('settings', function (Blueprint $table) {
            // Driver Points Settings
            $table->integer('points_driver_per_trip')->default(5);
            $table->integer('points_driver_visa_bonus')->default(5);
            $table->integer('points_driver_five_star')->default(10);
            $table->integer('points_driver_cancel_penalty')->default(20);

            // User Points Settings
            $table->integer('points_user_per_trip')->default(2);
            $table->integer('points_user_visa_bonus')->default(3);
            $table->integer('points_user_cancel_penalty')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'points_driver_per_trip',
                'points_driver_visa_bonus',
                'points_driver_five_star',
                'points_driver_cancel_penalty',
                'points_user_per_trip',
                'points_user_visa_bonus',
                'points_user_cancel_penalty'
            ]);
        });
    }
};
