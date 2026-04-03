<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrderStatusEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'searching', 'negotiating', 'user_accept_offer', 'payment_pending', 'payment_paid', 'payment_failed', 'payment_updated', 'assigned', 'driver_on_a_way', 'arrived', 'on_trip', 'completed', 'canceled', 'payment_required', 'placed', 'started') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverting to the approximate original state from the database/migrations/2024_05_29_000018_create_orders_table.php
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('searching', 'placed', 'started', 'completed', 'canceled') NULL");
    }
}
