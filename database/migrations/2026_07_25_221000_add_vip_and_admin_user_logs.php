<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'is_vip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_vip')->default(false)->after('is_active');
            });
        }

        if (!Schema::hasTable('admin_user_audit_logs')) {
            Schema::create('admin_user_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->string('action');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_audit_logs');
        if (Schema::hasColumn('users', 'is_vip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_vip');
            });
        }
    }
};
