<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'sms_enabled')) {
                $table->boolean('sms_enabled')->default(true)->after('id');
                $table->string('sms_base_url')->nullable()->default('http://smssmartegypt.com/sms/api')->after('sms_enabled');
                $table->string('sms_username')->nullable()->after('sms_base_url');
                $table->string('sms_password')->nullable()->after('sms_username');
                $table->string('sms_sender')->nullable()->default('Shakshak')->after('sms_password');
                $table->text('sms_message_template')->nullable()->after('sms_sender');
                $table->text('sms_shipping_template')->nullable()->after('sms_message_template');
            }
        });

        if (!Schema::hasTable('sms_logs')) {
            Schema::create('sms_logs', function (Blueprint $table) {
                $table->id();
                $table->string('mobile');
                $table->text('message');
                $table->string('type')->default('shipping'); // shipping | otp | test | custom
                $table->string('status')->default('pending'); // success | failed | pending
                $table->text('gateway_response')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'sms_enabled',
                'sms_base_url',
                'sms_username',
                'sms_password',
                'sms_sender',
                'sms_message_template',
                'sms_shipping_template',
            ]);
        });
    }
};
