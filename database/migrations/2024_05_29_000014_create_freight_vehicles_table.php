<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreightVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('freight_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('name')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('enable')->default(0)->nullable();
            
            $table->decimal('height')->nullable();
            $table->decimal('width')->nullable();
            
            $table->string('image')->nullable();
            $table->decimal('km_charge', 13, 4)->nullable();
            
            $table->timestamps();
            
            $table->softDeletes();
        });
    }
}
