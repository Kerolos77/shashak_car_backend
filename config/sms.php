<?php
// config/sms.php

return [
    'base_url' => env('SMS_BASE_URL', 'http://smssmartegypt.com/sms/api'),
    'username' => env('SMS_USERNAME', ''),
    'password' => env('SMS_PASSWORD', ''),
    'sender' => env('SMS_SENDER', 'SENDER'),
    'message_template' => env('SMS_MESSAGE_TEMPLATE', 'Your verification code is :otp'),
    ];
