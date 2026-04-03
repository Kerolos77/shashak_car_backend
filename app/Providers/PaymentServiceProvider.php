<?php

namespace App\Providers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    // 

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('payment_methods')) {
            $this->app->booted(function () {
                $paymob = PaymentMethod::where('name', '=', 'PAYMOB')->first();

                if ($paymob) {
                    config([
                        'nafezly-payments.PAYMOB_API_KEY' => $paymob->api_key,
                        'nafezly-payments.PAYMOB_INTEGRATION_ID' => $paymob->integration_id,
                        'nafezly-payments.PAYMOB_WALLET_INTEGRATION_ID' => $paymob->wallet_integration_id,
                        'nafezly-payments.PAYMOB_IFRAME_ID' => $paymob->iframe_id,
                        'nafezly-payments.PAYMOB_HMAC' => $paymob->hmac,
                        'nafezly-payments.PAYMOB_CURRENCY' => $paymob->currency,
                    ]);
                }
                # 
                $PerfectMoney = PaymentMethod::where('name', '=', 'PERFECTMONEY')->first();

                if ($PerfectMoney) {

                    config([
                        'nafezly-payments.PERFECTMONEY_ID' => $PerfectMoney->api_key,
                        'nafezly-payments.PERFECTMONEY_PASSPHRASE' => $PerfectMoney->passphrase,
                    ]);

                }


                #HYPERPAY
                $hyperpay = PaymentMethod::where('name', '=', 'HYPERPAY')->first();
                if ($hyperpay) {
                    config([
                        'nafezly-payments.HYPERPAY_BASE_URL' => $hyperpay->base_url,
                        'nafezly-payments.HYPERPAY_URL' => $hyperpay->base_url . "/v1/checkouts",
                        'nafezly-payments.HYPERPAY_TOKEN' => $hyperpay->token,
                        'nafezly-payments.HYPERPAY_CREDIT_ID' => $hyperpay->credit_id,
                        'nafezly-payments.HYPERPAY_MADA_ID' => $hyperpay->mada_id,
                        'nafezly-payments.HYPERPAY_APPLE_ID' => $hyperpay->apple_id,
                        'nafezly-payments.HYPERPAY_CURRENCY' => $hyperpay->currency,
                    ]);
                }
                #KASHIER
                $kashier = PaymentMethod::where('name', '=', 'KASHIER')->first();
                if ($kashier) {
                    config([
                        'nafezly-payments.KASHIER_ACCOUNT_KEY' => $kashier->account_key,
                        'nafezly-payments.KASHIER_IFRAME_KEY' => $kashier->iframe_key,
                        'nafezly-payments.KASHIER_TOKEN' => $kashier->token,
                        'nafezly-payments.KASHIER_URL' => $kashier->base_url,// env('KASHIER_URL', "https://checkout.kashier.io"),
                        'nafezly-payments.KASHIER_MODE' => $kashier->mode, // env('KASHIER_MODE', "test"), //live or test
                        'nafezly-payments.KASHIER_CURRENCY' => $kashier->currency, // env('KASHIER_CURRENCY', "EGP"),
                        'nafezly-payments.KASHIER_WEBHOOK_URL' => $kashier->webhook_url, // env('KASHIER_WEBHOOK_URL'),
                    ]);
                }
                #FAWRY
                $fawry = PaymentMethod::where('name', '=', 'FAWRY')->first();
                if ($fawry) {
                    config([
                        'nafezly-payments.FAWRY_BASE_URL' => $fawry->base_url,// env('FAWRY_BASE_URL', "https://atfawry.fawrystaging.com"),
                        'nafezly-payments.FAWRY_MERCHANT_CODE' => $fawry->merchant_code,
                        'nafezly-payments.FAWRY_SECURITY_KEY' => $fawry->security_key,
                        'nafezly-payments.FAWRY_CURRENCY' => $fawry->currency,
                    ]);
                }
                #PayPal
                $paypal = PaymentMethod::where('name', '=', 'PAYPAL')->first();
                if ($paypal) {
                    config([
                        'nafezly-payments.PAYPAL_CLIENT_ID' => $paypal->client_id,
                        'nafezly-payments.PAYPAL_SECRET' => $paypal->secret_key,
                        'nafezly-payments.PAYPAL_CURRENCY' => $paypal->currency,
                        'nafezly-payments.PAYPAL_MODE' => $paypal->mode,
                    ]);
                }
                #THAWANI
                $thawani = PaymentMethod::where('name', '=', 'THAWANI')->first();
                if ($thawani) {
                    config([
                        'nafezly-payments.THAWANI_API_KEY' => $thawani->api_key,
                        'nafezly-payments.THAWANI_URL' => $thawani->base_url,// env('THAWANI_URL', "https://uatcheckout.thawani.om/"),
                        'nafezly-payments.THAWANI_PUBLISHABLE_KEY' => $thawani->publishable_key,
                        'nafezly-payments.THAWANI_CURRENCY' => $thawani->currency,
                    ]);
                }
                #TAP
                $tap = PaymentMethod::where('name', '=', 'TAP')->first();
                if ($tap) {
                    config([
                        'nafezly-payments.TAP_CURRENCY' => $tap->currency,
                        'nafezly-payments.TAP_SECRET_KEY' => $tap->secret_key,
                        'nafezly-payments.TAP_PUBLIC_KEY' => $tap->public_key,
                        'nafezly-payments.TAP_LANG_KEY' => $tap->lang_key,
                    ]);
                }
                #OPAY
                $opay = PaymentMethod::where('name', '=', 'OPAY')->first();
                if ($opay) {
                    config([
                        'nafezly-payments.OPAY_CURRENCY' => $opay->currency,
                        'nafezly-payments.OPAY_SECRET_KEY' => $opay->secret_key,
                        'nafezly-payments.OPAY_PUBLIC_KEY' => $opay->public_key,
                        'nafezly-payments.OPAY_MERCHANT_ID' => $opay->merchant_id,
                        'nafezly-payments.OPAY_COUNTRY_CODE' => $opay->country_code,
                        'nafezly-payments.OPAY_BASE_URL' => $opay->base_url, // https://sandboxapi.opaycheckout.com //https://api.opaycheckout.com for production
                    ]);
                }
                #Paytabs
                $paytabs = PaymentMethod::where('name', '=', 'PAYTABS')->first();
                if ($paytabs) {
                    config([
                        'nafezly-payments.PAYTABS_PROFILE_ID' => $paytabs->profile_id,
                        'nafezly-payments.PAYTABS_SERVER_KEY' => $paytabs->server_key,
                        'nafezly-payments.PAYTABS_BASE_URL' => $paytabs->base_url, // env('PAYTABS_BASE_URL', "https://secure-egypt.paytabs.com"),
                        'nafezly-payments.PAYTABS_CHECKOUT_LANG' => $paytabs->lang_key,//env('PAYTABS_CHECKOUT_LANG', "AR"),
                        'nafezly-payments.PAYTABS_CURRENCY' => $paytabs->currency,
                    ]);
                }
                #Binance
                $binance = PaymentMethod::where('name', '=', 'BINANCE')->first();
                if ($binance) {
                    config([
                        'nafezly-payments.BINANCE_API' => $binance->api_key,
                        'nafezly-payments.BINANCE_SECRET' => $binance->secret_key,
                    ]);
                }
                #NowPayments
                $nowpayments = PaymentMethod::where('name', '=', 'NOWPAYMENTS')->first();
                if ($nowpayments) {
                    config([
                        'nafezly-payments.NOWPAYMENTS_API_KEY' => $nowpayments->api_key,
                    ]);
                }

            });
        }
    }
}
