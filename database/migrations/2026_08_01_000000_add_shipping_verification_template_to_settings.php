<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'sms_shipping_verification_template')) {
                $table->text('sms_shipping_verification_template')->nullable()->after('sms_shipping_template');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'sms_shipping_verification_template')) {
                $table->dropColumn('sms_shipping_verification_template');
            }
        });
    }
};
