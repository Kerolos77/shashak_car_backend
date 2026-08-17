<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('driver_registration_logs')) {
            Schema::create('driver_registration_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->string('action', 50)->default('pending'); // pending, approved, rejected, resubmitted
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('driver_profiles')) {
            Schema::table('driver_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('driver_profiles', 'latest_rejection_reason')) {
                    $table->text('latest_rejection_reason')->nullable()->after('status');
                }
            });

            try {
                DB::statement("ALTER TABLE `driver_profiles` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
            } catch (\Exception $e) {
                // Fallback for DB engines where DB statement might differ
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_registration_logs');
        if (Schema::hasTable('driver_profiles')) {
            Schema::table('driver_profiles', function (Blueprint $table) {
                if (Schema::hasColumn('driver_profiles', 'latest_rejection_reason')) {
                    $table->dropColumn('latest_rejection_reason');
                }
            });
        }
    }
};
