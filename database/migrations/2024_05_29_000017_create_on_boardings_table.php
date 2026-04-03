<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnBoardingsTable extends Migration
{
    public function up()
    {
        Schema::create('on_boardings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
