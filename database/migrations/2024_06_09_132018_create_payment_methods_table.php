<?php

use App\Models\PaymentMethod;
use Faker\Provider\ar_EG\Payment;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('api_key')->nullable();
            $table->string('integration_id')->nullable();
            $table->string('iframe_id')->nullable();
            $table->string('hmac')->nullable();
            $table->string('currency')->default('EGP');
            $table->string('base_url')->nullable();
            $table->string('url')->nullable();
            $table->string('token')->nullable();
            $table->string('credit_id')->nullable();
            $table->string('mada_id')->nullable();
            $table->string('apple_id')->nullable();
            $table->string('account_key')->nullable();
            $table->string('iframe_key')->nullable();
            $table->string('mode')->default('test');
            $table->string('webhook_url')->nullable();
            $table->boolean('enabled')->default(false);
            $table->string('secret_key')->nullable();
            $table->string('public_key')->nullable();
            $table->string('lang_key')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('publishable_key')->nullable();
            $table->string('security_key')->nullable();
            $table->string('profile_id')->nullable();
            $table->string('server_key')->nullable();
            $table->string('passphrase')->nullable();
            $table->string('wallet_integration_id')->nullable();
            $table->string('secret')->nullable();
            $table->string('merchant')->nullable();
            $table->string('display_mode')->nullable();
            $table->string('pay_mode')->nullable();
            $table->string('merchant_code')->nullable();
            $table->string('client_id')->nullable();
            $table->string('credit_secret')->nullable();
            $table->string('credit_mode')->nullable();
            $table->string('credit_currency')->nullable();
            $table->string('country_code')->nullable();
            $table->string('checkout_lang')->nullable();
            $table->string('api')->nullable();
            $table->string('secret_word_1')->nullable();
            $table->string('additional_api_key')->nullable();
            $table->string('private_key')->nullable();
            $table->string('enot_key')->nullable();
            $table->string('merchant_username')->nullable();
            $table->string('merchant_secret')->nullable();
            $table->string('project_id')->nullable();
            $table->string('secret_word_2')->nullable();
            $table->string('app_id')->nullable();
            $table->timestamps();
        });
        $methods = [
            'PAYMOB',
            'HYPERPAY',
            'KASHIER',
            'FAWRY',
            'PayPal',
            'THAWANI',
            'TAP',
            'OPAY',
            'Paytabs',
            'Binance',
            'NowPayments',
            'Payeer',
            'Perfectmoney',
            'TELR',
            'CLICKPAY',
            'COINPAYMENTS',
            'BigPay',
            'ENOT_KEY',
            'PAYCEC',
            'PAYPAL',
            'PRIME',
            'PAYLINK'
        ];
        foreach ($methods as $method) {
            PaymentMethod::create([
                'name' => $method,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
