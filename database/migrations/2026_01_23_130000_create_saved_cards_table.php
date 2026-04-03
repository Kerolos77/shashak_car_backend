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
        Schema::create('saved_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('card_token')->comment('Paymob card token for recurring payments');
            $table->string('card_subtype')->nullable()->comment('Card brand: VISA, Mastercard, etc.');
            $table->string('masked_pan')->comment('Last 4 digits e.g., **** 1234');
            $table->boolean('is_default')->default(false);
            $table->string('card_holder_name')->nullable();
            $table->string('expiry_month', 2)->nullable();
            $table->string('expiry_year', 4)->nullable();
            $table->string('paymob_order_id')->nullable()->comment('Original order ID from Paymob');
            $table->string('paymob_transaction_id')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
            $table->unique(['user_id', 'card_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_cards');
    }
};
