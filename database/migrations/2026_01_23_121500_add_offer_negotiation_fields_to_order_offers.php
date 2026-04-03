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
        Schema::table('order_offers', function (Blueprint $table) {
            $table->enum('sender_type', ['driver', 'user'])->default('driver')->after('driver_id');
            $table->enum('status', ['pending', 'accepted', 'denied'])->default('pending')->after('offer_rate');
            $table->decimal('user_counter_offer', 14, 3)->nullable()->after('status');
            
            // Add user_id for user counter-offers
            $table->foreignId('user_id')->nullable()->after('order_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_offers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['sender_type', 'status', 'user_counter_offer', 'user_id']);
        });
    }
};
