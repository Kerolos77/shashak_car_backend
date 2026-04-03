<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // pending = not yet paid, paid = charged successfully, failed = charge failed
            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                ->default('pending')
                ->after('payment_type')
                ->comment('Card payment status — cash orders remain pending');

            // Store the paymob intention/order ID to link webhook → order
            $table->string('paymob_order_id')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paymob_order_id']);
        });
    }
};
