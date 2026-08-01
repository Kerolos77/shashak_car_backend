<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'intercity_enabled')) {
                $table->dropColumn('intercity_enabled');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'intercity_type')) {
                $table->dropColumn('intercity_type');
            }
        });

        Schema::dropIfExists('orders_intercity');
        Schema::dropIfExists('intercity_services');
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'intercity_enabled')) {
                $table->boolean('intercity_enabled')->default(true)->after('travel_enabled');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'intercity_type')) {
                $table->boolean('intercity_type')->default(0)->nullable();
            }
        });
    }
};
