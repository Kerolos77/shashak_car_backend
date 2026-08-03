<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_coupons')) {
            Schema::create('user_coupons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->boolean('is_used')->default(false);
                $table->timestamps();

                $table->unique(['coupon_id', 'user_id']);
            });
        }

        if (!Schema::hasColumn('coupons', 'title')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->string('title')->nullable()->after('code');
            });
        }

        if (!Schema::hasColumn('coupons', 'is_public')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->boolean('is_public')->default(true)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
};
