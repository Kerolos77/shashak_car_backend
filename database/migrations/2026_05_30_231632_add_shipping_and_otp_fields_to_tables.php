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
        // 1. Modify orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_shipping_order')->default(false)->after('inter_city');
            $table->string('pickup_otp', 10)->nullable()->after('is_shipping_order');
            $table->string('delivery_otp', 10)->nullable()->after('pickup_otp');
            $table->string('receiver_name')->nullable()->after('delivery_otp');
            $table->string('receiver_phone')->nullable()->after('receiver_name');
            
            // Tracking timestamps
            $table->timestamp('driver_arrived_at_sender_at')->nullable();
            $table->timestamp('sender_confirmed_handover_at')->nullable();
            $table->timestamp('driver_confirmed_pickup_at')->nullable();
            $table->timestamp('driver_confirmed_cash_at')->nullable();
            $table->timestamp('driver_arrived_at_receiver_at')->nullable();
            $table->timestamp('driver_confirmed_delivery_at')->nullable();
            $table->timestamp('receiver_confirmed_delivery_at')->nullable();
        });

        // 2. Modify users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('national_id')->nullable()->after('gender');
            $table->string('national_id_front')->nullable()->after('national_id');
            $table->string('national_id_back')->nullable()->after('national_id_front');
            $table->string('national_id_selfie')->nullable()->after('national_id_back');
        });

        // 3. Modify settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('min_driver_wallet_for_shipping', 10, 2)->default(0.00)->after('commission_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'is_shipping_order',
                'pickup_otp',
                'delivery_otp',
                'receiver_name',
                'receiver_phone',
                'driver_arrived_at_sender_at',
                'sender_confirmed_handover_at',
                'driver_confirmed_pickup_at',
                'driver_confirmed_cash_at',
                'driver_arrived_at_receiver_at',
                'driver_confirmed_delivery_at',
                'receiver_confirmed_delivery_at'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'national_id',
                'national_id_front',
                'national_id_back',
                'national_id_selfie'
            ]);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('min_driver_wallet_for_shipping');
        });
    }
};
