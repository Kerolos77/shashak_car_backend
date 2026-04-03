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
            $table->string('paymob_api_key', 1000)->nullable();
            $table->string('paymob_hmac')->nullable();
            $table->string('paymob_card_integration_id')->nullable();
            $table->string('paymob_wallet_integration_id')->nullable();
            $table->string('paymob_iframe_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'paymob_api_key',
                'paymob_hmac',
                'paymob_card_integration_id',
                'paymob_wallet_integration_id',
                'paymob_iframe_id',
            ]);
        });
    }
};
