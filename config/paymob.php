<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paymob API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Paymob payment gateway integration with card tokenization
    | support. These values are pulled from the .env file.
    |
    */

    'api_key' => env('PAYMOB_API_KEY'),
    'secret_key' => env('PAYMOB_SECRET_KEY'),
    'public_key' => env('PAYMOB_PUBLIC_KEY'),
    'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
    'mode' => env('PAYMOB_MODE', 'test'),
    'integration_id' => env('PAYMOB_INTEGRATION_ID'),
    'wallet_integration_id' => env('PAYMOB_WALLET_INTEGRATION_ID'),  // e-wallets: Vodafone Cash, Orange, etc.

    /*
    |--------------------------------------------------------------------------
    | API Base URLs
    |--------------------------------------------------------------------------
    */
    'base_url' => 'https://accept.paymob.com/v1/intention/',
    'api_url' => 'https://accept.paymob.com/api',

    /*
    |--------------------------------------------------------------------------
    | Callback URLs
    |--------------------------------------------------------------------------
    */
    'callback_url' => env('APP_URL') . '/api/v1/paymob/webhook',
    'return_url' => env('APP_URL') . '/payment/complete',

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */
    'currency' => 'EGP',
];
