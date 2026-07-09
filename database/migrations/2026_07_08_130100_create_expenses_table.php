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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // e.g. paymob, digitalocean, google_cloud, domain, other
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EGP');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('amount_egp', 12, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->boolean('is_automated')->default(false);
            $table->string('reference_id')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamps();

            // Unique index to prevent importing the same invoice or paymob commission twice
            $table->unique(['category', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
