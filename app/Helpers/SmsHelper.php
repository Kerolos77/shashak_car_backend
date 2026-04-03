<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsHelper
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $sender;
    protected $messageTemplate;

    public function __construct()
    {
        $this->baseUrl = env('SMS_BASE_URL', 'http://smssmartegypt.com/sms/api');
        $this->username = env('SMS_USERNAME');
        $this->password = env('SMS_PASSWORD');
        $this->sender = env('SMS_SENDER');
        $this->messageTemplate = env('SMS_MESSAGE_TEMPLATE', 'Your verification code is :otp');
    }

    // public function sendSms($mobile, $otp)
    // {
    //     // ?????? ??? :otp ?? ??????? ??????
    //     $message = str_replace(':otp', $otp, $this->messageTemplate);

    //     try {
    //         $response = Http::get($this->baseUrl, [
    //             'username'   => $this->username,
    //             'password'   => $this->password,
    //             'sendername' => $this->sender,
    //             'mobiles'    => $mobile,
    //             'message'    => $message,
    //         ]);

    //         return $response->body();

    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
public function sendSms($mobile, $otp)
{
    if (preg_match('/^0/', $mobile)) {
        $mobile = '20' . substr($mobile, 1);
    }
    
    $message = str_replace(':otp', $otp, $this->messageTemplate);
    // $encodedMessage = urlencode($message);

    try {
        $params = [
            'username'   => $this->username,
            'password'   => $this->password,
            'sendername' => $this->sender,
            'mobiles'    => $mobile,
            'message'    => $message,
        ];
        //return json_encode($params);
        $url = $this->baseUrl . '?' . http_build_query($params);
$url = str_replace('%2B', '+', $url); // ?? ??? ????

        

        $response = Http::withOptions(['verify' => false])->get($url);
        // return $url;
        //$response = Http::withOptions(['verify' => false])->get($url);

        return $response->json();

    } catch (\Exception $e) {
        return $e->getMessage();
    }
}


}
