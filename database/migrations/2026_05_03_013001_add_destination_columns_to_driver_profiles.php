<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('driver_profiles', function (Blueprint $table) {
            $table->decimal('destination_lat', 10, 8)->nullable();
            $table->decimal('destination_long', 11, 8)->nullable();
            $table->string('destination_address')->nullable();
            $table->boolean('is_heading_destination')->default(false);
        });
    }

    public function down()
    {
        Schema::table('driver_profiles', function (Blueprint $table) {
            $table->dropColumn(['destination_lat', 'destination_long', 'destination_address', 'is_heading_destination']);
        });
    }
};
