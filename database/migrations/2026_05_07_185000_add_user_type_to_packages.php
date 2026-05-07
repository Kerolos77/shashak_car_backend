<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('driver_packages', function (Blueprint $table) {
            $table->string('user_type')->default('driver')->after('name'); // driver, user
        });
    }

    public function down()
    {
        Schema::table('driver_packages', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
};
