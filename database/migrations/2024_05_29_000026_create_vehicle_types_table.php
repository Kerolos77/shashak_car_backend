<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enable')->default(0)->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
