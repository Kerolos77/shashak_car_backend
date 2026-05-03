<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('driver_trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('status', ['ignored', 'accepted', 'rejected'])->default('ignored');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_trip_requests');
    }
};
