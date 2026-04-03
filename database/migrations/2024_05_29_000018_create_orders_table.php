<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('driver_id')->unsigned()->nullable();
            $table->enum('payment_type', ['cash', 'wallet']);
            $table->boolean('paid')->default(0)->nullable();
            $table->string('distance')->nullable();
            $table->string('distance_type')->nullable();
            $table->string('destination_address')->nullable();
            $table->string('destination_lat')->nullable();
            $table->string('destination_long')->nullable();
            $table->string('source_address')->nullable();
            $table->string('source_lat')->nullable();
            $table->string('source_long')->nullable();
            $table->decimal('offer_rate', 14, 3)->nullable();
            $table->decimal('final_rate', 14, 3)->nullable();
            $table->decimal('commission', 14, 3)->nullable();
            $table->string('destination_City')->nullable();
            $table->string('source_city')->nullable();
            $table->string('parcel_dimension')->nullable();
            $table->string('parcel_image')->nullable();
            $table->string('parcel_weight')->nullable();
            $table->dateTime('when_date')->nullable();
            $table->boolean('inter_city')->default(0);
            $table->tinyInteger('number_of_passenger')->default(4);
            $table->enum('status', ['searching', 'placed', 'started', 'completed', 'canceled'])->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9827720')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();


            $table->timestamp('is_placed')->nullable();
            $table->timestamp('is_started')->nullable();
            $table->timestamp('is_accept')->nullable();
            $table->timestamp('is_complete')->nullable();
            $table->timestamp('is_canceled')->nullable();

            $table->foreignId('canceled_by')->nullable();
            $table->foreign('canceled_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
