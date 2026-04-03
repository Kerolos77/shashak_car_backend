<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Expand payment_type ENUM to include card and saved_card
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_type` ENUM('cash', 'wallet', 'card', 'saved_card')
            NOT NULL DEFAULT 'cash'
        ");
    }

    public function down(): void
    {
        // Revert to original values (existing card/saved_card rows will become 'cash')
        DB::statement("UPDATE `orders` SET `payment_type` = 'cash' WHERE `payment_type` IN ('card', 'saved_card')");
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_type` ENUM('cash', 'wallet')
            NOT NULL DEFAULT 'cash'
        ");
    }
};
