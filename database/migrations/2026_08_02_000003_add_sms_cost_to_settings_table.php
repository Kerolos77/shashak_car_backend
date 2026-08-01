<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'sms_cost_per_message')) {
                $table->decimal('sms_cost_per_message', 8, 4)->default(0.2500)->after('sms_shipping_verification_template');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'sms_cost_per_message')) {
                $table->dropColumn('sms_cost_per_message');
            }
        });
    }
};
