<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSplitPaymentColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('wallet_paid', 15, 2)->default(0)->after('offer_rate');
            $table->decimal('card_paid', 15, 2)->default(0)->after('wallet_paid');
            $table->boolean('is_escrow')->default(false)->after('card_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['wallet_paid', 'card_paid', 'is_escrow']);
        });
    }
}
