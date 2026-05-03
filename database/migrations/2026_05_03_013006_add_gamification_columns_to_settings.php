<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('visa_rejection_limit')->default(3);
            $table->integer('visa_restriction_duration_minutes')->default(120);
            $table->integer('points_per_visa_trip')->default(10);
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['visa_rejection_limit', 'visa_restriction_duration_minutes', 'points_per_visa_trip']);
        });
    }
};
