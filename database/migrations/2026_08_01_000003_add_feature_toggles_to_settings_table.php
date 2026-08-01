<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'shipping_enabled')) {
                $table->boolean('shipping_enabled')->default(true)->after('sms_enabled');
            }
            if (!Schema::hasColumn('settings', 'ride_enabled')) {
                $table->boolean('ride_enabled')->default(true)->after('shipping_enabled');
            }
            if (!Schema::hasColumn('settings', 'travel_enabled')) {
                $table->boolean('travel_enabled')->default(true)->after('ride_enabled');
            }
            if (!Schema::hasColumn('settings', 'intercity_enabled')) {
                $table->boolean('intercity_enabled')->default(true)->after('travel_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'shipping_enabled')) {
                $table->dropColumn('shipping_enabled');
            }
            if (Schema::hasColumn('settings', 'ride_enabled')) {
                $table->dropColumn('ride_enabled');
            }
            if (Schema::hasColumn('settings', 'travel_enabled')) {
                $table->dropColumn('travel_enabled');
            }
            if (Schema::hasColumn('settings', 'intercity_enabled')) {
                $table->dropColumn('intercity_enabled');
            }
        });
    }
};
