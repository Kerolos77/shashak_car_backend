<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('digitalocean_api_token')->nullable();
            $table->text('gcp_service_account_json')->nullable();
            $table->string('gcp_billing_account_id')->nullable();
            $table->decimal('paymob_card_commission_percent', 5, 2)->default(2.75);
            $table->decimal('paymob_card_commission_fixed', 5, 2)->default(3.00);
            $table->decimal('paymob_wallet_commission_percent', 5, 2)->default(1.50);
            $table->decimal('paymob_wallet_commission_fixed', 5, 2)->default(0.00);
            $table->decimal('usd_to_egp_exchange_rate', 10, 2)->default(50.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'digitalocean_api_token',
                'gcp_service_account_json',
                'gcp_billing_account_id',
                'paymob_card_commission_percent',
                'paymob_card_commission_fixed',
                'paymob_wallet_commission_percent',
                'paymob_wallet_commission_fixed',
                'usd_to_egp_exchange_rate',
            ]);
        });
    }
};
