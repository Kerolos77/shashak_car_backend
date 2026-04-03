<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Expand payment_type ENUM to include split payment types
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_type` ENUM('cash', 'wallet', 'card', 'saved_card', 'wallet_card', 'wallet_cash')
            NOT NULL DEFAULT 'cash'
        ");
    }

    public function down(): void
    {
        // Revert to original values
        DB::statement("UPDATE `orders` SET `payment_type` = 'cash' WHERE `payment_type` IN ('wallet_card', 'wallet_cash')");
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_type` ENUM('cash', 'wallet', 'card', 'saved_card')
            NOT NULL DEFAULT 'cash'
        ");
    }
};
