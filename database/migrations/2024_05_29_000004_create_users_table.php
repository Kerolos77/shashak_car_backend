<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('marketopia_countries')->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('marketopia_cities')->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->string('fcm_token')->nullable();
            $table->boolean('is_active')->default(0)->nullable();
            $table->boolean('is_online')->default(0)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('reviews_count')->nullable();
            $table->string('reviews_sum')->nullable();
            $table->string('wallet_amount')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }
}
