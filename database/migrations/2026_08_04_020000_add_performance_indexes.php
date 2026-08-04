<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add performance indexes on the most-queried columns.
 *
 * Columns targeted:
 *  - users.phone_number         : used in every auth lookup
 *  - otps.phone + otps.otp     : used in every OTP verify
 *  - coupons.code               : used in coupon validate
 *  - orders.user_id             : used in get-user-active-ride
 *  - orders.driver_id           : used in get-driver-active-ride
 *  - orders.status              : used in filtering active trips
 *  - coupon_usages.user_id      : used to check per-user usage count
 */
return new class extends Migration
{
    public function up(): void
    {
        // users: phone_number (already might exist — use ignore)
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'users_phone_number_index')) {
                $table->index('phone_number', 'users_phone_number_index');
            }
        });

        // otps: (phone, otp) composite + phone alone
        Schema::table('otps', function (Blueprint $table) {
            if (!$this->indexExists('otps', 'otps_phone_otp_index')) {
                $table->index(['phone', 'otp'], 'otps_phone_otp_index');
            }
            if (!$this->indexExists('otps', 'otps_phone_index')) {
                $table->index('phone', 'otps_phone_index');
            }
        });

        // coupons: code (unique already, but add regular index for partial lookups)
        // Note: unique() already creates an index, so we skip to avoid duplicate

        // orders: user_id, driver_id, status
        Schema::table('orders', function (Blueprint $table) {
            if (!$this->indexExists('orders', 'orders_user_id_status_index')) {
                $table->index(['user_id', 'status'], 'orders_user_id_status_index');
            }
            if (!$this->indexExists('orders', 'orders_driver_id_status_index')) {
                $table->index(['driver_id', 'status'], 'orders_driver_id_status_index');
            }
        });

        // coupon_usages: (coupon_id, user_id)
        Schema::table('coupon_usages', function (Blueprint $table) {
            if (!$this->indexExists('coupon_usages', 'coupon_usages_coupon_user_index')) {
                $table->index(['coupon_id', 'user_id'], 'coupon_usages_coupon_user_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', fn($t) => $t->dropIndexIfExists('users_phone_number_index'));
        Schema::table('otps', function ($t) {
            $t->dropIndexIfExists('otps_phone_otp_index');
            $t->dropIndexIfExists('otps_phone_index');
        });
        Schema::table('orders', function ($t) {
            $t->dropIndexIfExists('orders_user_id_status_index');
            $t->dropIndexIfExists('orders_driver_id_status_index');
        });
        Schema::table('coupon_usages', fn($t) => $t->dropIndexIfExists('coupon_usages_coupon_user_index'));
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $sm = \Illuminate\Support\Facades\DB::getDoctrineSchemaManager();
        $indexes = $sm->listTableIndexes($table);
        return isset($indexes[strtolower($indexName)]);
    }
};
