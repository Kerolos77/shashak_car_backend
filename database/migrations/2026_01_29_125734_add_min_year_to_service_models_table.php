<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_models', function (Blueprint $table) {
            $table->integer('min_year')->nullable()->after('model_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_models', function (Blueprint $table) {
            $table->dropColumn('min_year');
        });
    }
};
