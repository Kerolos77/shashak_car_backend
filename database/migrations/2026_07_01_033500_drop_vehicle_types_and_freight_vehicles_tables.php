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
        Schema::dropIfExists('vehicle_types');
        Schema::dropIfExists('freight_vehicles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('enable')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('freight_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('km_charge', 15, 2)->nullable();
            $table->decimal('length', 15, 2)->nullable();
            $table->decimal('width', 15, 2)->nullable();
            $table->decimal('height', 15, 2)->nullable();
            $table->boolean('enable')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
