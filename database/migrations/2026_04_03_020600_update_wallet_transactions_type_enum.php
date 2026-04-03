<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateWalletTransactionsTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL because Laravel's ->enum()->change() has limitations with some DB drivers
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('voucher', 'bonus', 'order', 'refund', 'compensation', 'deposit', 'withdraw', 'transfer_in', 'transfer_out') DEFAULT 'voucher'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('voucher', 'bonus', 'order', 'refund', 'compensation') DEFAULT 'voucher'");
    }
}
