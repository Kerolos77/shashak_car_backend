<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Temporarily relax column to VARCHAR so we can write new values
        DB::statement("ALTER TABLE `order_offers` MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");

        // Step 2: Migrate old generic values → new granular values
        DB::statement("UPDATE `order_offers` SET `status` = 'driver_accepted' WHERE `status` = 'accepted'");
        DB::statement("UPDATE `order_offers` SET `status` = 'user_denied'     WHERE `status` = 'denied'");

        // Step 3: Lock back to strict ENUM with all new values
        DB::statement("
            ALTER TABLE `order_offers`
            MODIFY COLUMN `status` ENUM(
                'pending',
                'countered',
                'user_accepted',
                'driver_accepted',
                'user_denied',
                'driver_canceled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Step 1: Relax to VARCHAR
        DB::statement("ALTER TABLE `order_offers` MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");

        // Step 2: Revert data
        DB::statement("UPDATE `order_offers` SET `status` = 'accepted' WHERE `status` IN ('user_accepted', 'driver_accepted')");
        DB::statement("UPDATE `order_offers` SET `status` = 'denied'   WHERE `status` IN ('user_denied', 'driver_canceled')");

        // Step 3: Restore original ENUM
        DB::statement("
            ALTER TABLE `order_offers`
            MODIFY COLUMN `status` ENUM(
                'pending',
                'accepted',
                'denied',
                'countered'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};

