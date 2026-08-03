<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add coupon-related fields to the orders table.
 *
 * Fields:
 *  - coupon_id       : FK to coupons (nullable, set when a coupon is applied)
 *  - coupon_code     : Stored snapshot of the code used (for audit trail)
 *  - discount_amount : The actual EGP amount that was discounted
 *  - original_amount : The fare before discount (= offer_rate before coupon applied)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')
                  ->nullable()
                  ->constrained('coupons')
                  ->onDelete('set null')
                  ->after('final_rate');

            $table->string('coupon_code', 50)->nullable()->after('coupon_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code');
            $table->decimal('original_amount', 10, 2)->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn(['coupon_id', 'coupon_code', 'discount_amount', 'original_amount']);
        });
    }
};
