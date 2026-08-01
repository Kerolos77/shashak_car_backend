<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'price_tiers')) {
                $table->json('price_tiers')->nullable()->after('km_charge');
            }
            if (!Schema::hasColumn('services', 'tier_pricing_type')) {
                $table->string('tier_pricing_type')->default('flat')->after('price_tiers'); // flat | cumulative
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'price_tiers')) {
                $table->dropColumn('price_tiers');
            }
            if (Schema::hasColumn('services', 'tier_pricing_type')) {
                $table->dropColumn('tier_pricing_type');
            }
        });
    }
};
