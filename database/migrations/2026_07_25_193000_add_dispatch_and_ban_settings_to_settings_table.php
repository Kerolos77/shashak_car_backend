<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->double('max_cash_pickup_distance_km')->default(10.0)->after('min_driver_wallet_for_shipping');
            $table->double('max_card_pickup_distance_km')->default(15.0)->after('max_cash_pickup_distance_km');
            $table->double('destination_mode_tolerance_km')->default(5.0)->after('max_card_pickup_distance_km');
            $table->boolean('auto_cash_ban_enabled')->default(true)->after('destination_mode_tolerance_km');
            $table->decimal('max_driver_cash_debt_limit', 10, 2)->default(200.00)->after('auto_cash_ban_enabled');
            $table->integer('cash_restriction_duration_minutes')->default(60)->after('max_driver_cash_debt_limit');
            $table->integer('max_consecutive_cancellations_before_ban')->default(3)->after('cash_restriction_duration_minutes');
            $table->decimal('min_driver_rating_for_cash', 3, 2)->default(4.00)->after('max_consecutive_cancellations_before_ban');
            $table->string('dispatch_priority_strategy')->default('distance')->after('min_driver_rating_for_cash');
            $table->json('city_override_settings')->nullable()->after('dispatch_priority_strategy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'max_cash_pickup_distance_km',
                'max_card_pickup_distance_km',
                'destination_mode_tolerance_km',
                'auto_cash_ban_enabled',
                'max_driver_cash_debt_limit',
                'cash_restriction_duration_minutes',
                'max_consecutive_cancellations_before_ban',
                'min_driver_rating_for_cash',
                'dispatch_priority_strategy',
                'city_override_settings',
            ]);
        });
    }
};
