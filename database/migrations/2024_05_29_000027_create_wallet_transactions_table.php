<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->decimal('amount', 10, 3)->default(0);
            $table->string('note')->nullable();

            $table->enum('type', ['voucher', 'bonus', 'order', 'refund', 'compensation'])->default('voucher');

            $table->foreignId('order_id')->nullable();
            $table->foreignId('driver_id')->nullable();
            $table->foreignId('user_id')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('driver_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
