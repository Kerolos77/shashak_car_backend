<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Temporary change to VARCHAR to avoid truncation during data update
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
        DB::statement("ALTER TABLE order_offers MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");

        // 2. Add new timestamp columns for granular tracking to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'arrived_at')) {
                $table->timestamp('arrived_at')->nullable()->after('assigned_at');
            }
            if (!Schema::hasColumn('orders', 'on_trip_at')) {
                $table->timestamp('on_trip_at')->nullable()->after('arrived_at');
            }
            if (!Schema::hasColumn('orders', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('on_trip_at');
            }
            if (!Schema::hasColumn('orders', 'canceled_at')) {
                $table->timestamp('canceled_at')->nullable()->after('completed_at');
            }
        });

        // 3. Migrate existing data/mapping (Best effort)
        DB::table('orders')->where('status', 'searching')->update(['status' => 'pending']);
        DB::table('orders')->where('status', 'placed')->update(['status' => 'assigned']);
        DB::table('orders')->where('status', 'started')->update(['status' => 'on_trip']);

        // 4. Final conversion to refined ENUMs
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'negotiating', 'assigned', 'arrived', 'on_trip', 'completed', 'canceled') DEFAULT 'pending'");
        DB::statement("ALTER TABLE order_offers MODIFY COLUMN status ENUM('pending', 'accepted', 'denied', 'countered') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Temporary VARCHAR
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(255)");
        DB::statement("ALTER TABLE order_offers MODIFY COLUMN status VARCHAR(255)");

        // 2. Drop columns
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['assigned_at', 'arrived_at', 'on_trip_at', 'completed_at', 'canceled_at']);
        });

        // 3. Revert data
        DB::table('orders')->where('status', 'assigned')->update(['status' => 'placed']);
        DB::table('orders')->where('status', 'on_trip')->update(['status' => 'started']);
        DB::table('orders')->where('status', 'pending')->update(['status' => 'searching']);
        DB::table('orders')->where('status', 'negotiating')->update(['status' => 'searching']);
        DB::table('orders')->where('status', 'arrived')->update(['status' => 'placed']);

        // 4. Revert to old ENUMs
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('searching', 'placed', 'started', 'completed', 'canceled') DEFAULT 'searching'");
        DB::statement("ALTER TABLE order_offers MODIFY COLUMN status ENUM('pending', 'accepted', 'denied') DEFAULT 'pending'");
    }
};
