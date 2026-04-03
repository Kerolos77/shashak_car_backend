<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('airport_lat')->nullable();
            $table->string('airport_lng')->nullable();
            $table->string('airport_name')->nullable();
            $table->string('city_location')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
