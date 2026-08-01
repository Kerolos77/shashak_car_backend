<?php

namespace App\Helpers;

use App\Models\Setting;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $sender;
    protected $messageTemplate;
    protected $shippingTemplate;
    protected $shippingVerificationTemplate;
    protected $isEnabled;

    public function __construct()
    {
        $settings = Setting::first();

        $this->isEnabled = $settings ? ($settings->sms_enabled ?? true) : true;
        $this->baseUrl = ($settings && !empty($settings->sms_base_url)) 
            ? $settings->sms_base_url 
            : config('sms.base_url', env('SMS_BASE_URL', 'http://smssmartegypt.com/sms/api'));

        $this->username = ($settings && !empty($settings->sms_username)) 
            ? $settings->sms_username 
            : config('sms.username', env('SMS_USERNAME', ''));

        $this->password = ($settings && !empty($settings->sms_password)) 
            ? $settings->sms_password 
            : config('sms.password', env('SMS_PASSWORD', ''));

        $this->sender = ($settings && !empty($settings->sms_sender)) 
            ? $settings->sms_sender 
            : config('sms.sender', env('SMS_SENDER', 'Shakshak'));

        $this->messageTemplate = ($settings && !empty($settings->sms_message_template)) 
            ? $settings->sms_message_template 
            : config('sms.message_template', env('SMS_MESSAGE_TEMPLATE', 'كود تفعيل حسابك في شقشق هو: :otp'));

        $this->shippingTemplate = ($settings && !empty($settings->sms_shipping_template)) 
            ? $settings->sms_shipping_template 
            : 'أهلاً بك، شحنتك رقم #:order_id مع السائق :driver_name في الطريق إليك. للتتبع المباشر استخدم الرابط: :tracking_link وكود الاستلام هو: :otp';

        $this->shippingVerificationTemplate = ($settings && !empty($settings->sms_shipping_verification_template)) 
            ? $settings->sms_shipping_verification_template 
            : 'أهلاً بك، تم إنشاء طلب شحن جديد موجه إليك برقم #:order_id من العميل :sender_name. كود التأكيد الخاص بالطلب هو: :otp. يرجى تزويده للمرسل لتأكيد الطلب.';
    }

    /**
     * Format Egyptian mobile number to international format (201XXXXXXXXX)
     */
    public function formatPhone($mobile)
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$mobile);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '20' . substr($cleanPhone, 1);
        }
        return $cleanPhone;
    }

    /**
     * Send OTP SMS
     */
    public function sendSms($mobile, $otp)
    {
        $formattedMobile = $this->formatPhone($mobile);
        $message = str_replace(':otp', $otp, $this->messageTemplate);
        return $this->sendCustomSms($formattedMobile, $message, 'otp');
    }

    /**
     * Send Shipping Order Delivery SMS to Receiver
     */
    public function sendShippingDeliverySms($mobile, $order)
    {
        $driverName = $order->driver ? ($order->driver->full_name ?? $order->driver->name) : ($order->driver_name ?? 'سائق شقشق');
        $trackingLink = "https://shakshak.net/track/" . $order->id;
        $otp = $order->delivery_otp;

        $message = str_replace(
            [':order_id', ':driver_name', ':tracking_link', ':otp'],
            [$order->id, $driverName, $trackingLink, $otp],
            $this->shippingTemplate
        );

        return $this->sendCustomSms($mobile, $message, 'shipping_delivery');
    }

    /**
     * Send Shipping Order Verification SMS to Receiver
     */
    public function sendShippingVerificationSms($mobile, $order)
    {
        $senderName = $order->user->name ?? 'العميل';
        $otp = $order->receiver_verification_otp;

        $message = str_replace(
            [':order_id', ':sender_name', ':otp'],
            [$order->id, $senderName, $otp],
            $this->shippingVerificationTemplate
        );

        return $this->sendCustomSms($mobile, $message, 'shipping_receiver_verification');
    }

    public function sendCustomSms($mobile, $message, $type = 'shipping')
    {
        $formattedMobile = $this->formatPhone($mobile);

        if (!$this->isEnabled) {
            Log::info("SMS sending is disabled in admin settings for {$formattedMobile}");
            $this->createLog($formattedMobile, $message, $type, 'disabled', ['reason' => 'SMS system disabled in admin panel']);
            return [
                'status' => 'disabled',
                'message' => 'SMS system is disabled in dashboard settings.'
            ];
        }

        if (empty($this->username) || empty($this->password)) {
            Log::warning("SMS Credentials Missing: Cannot send SMS to {$formattedMobile}.");
            $this->createLog($formattedMobile, $message, $type, 'failed', ['error' => 'Credentials missing in settings or .env']);
            return [
                'status' => 'error',
                'message' => 'SMS gateway credentials not configured in dashboard or .env file.'
            ];
        }

        try {
            $params = [
                'username'   => $this->username,
                'password'   => $this->password,
                'sendername' => $this->sender,
                'mobiles'    => $formattedMobile,
                'message'    => $message,
            ];

            $url = $this->baseUrl . '?' . http_build_query($params);
            $url = str_replace('%2B', '+', $url);

            Log::info("Sending SMS to {$formattedMobile} via SMS Gateway");

            $response = Http::withOptions(['verify' => false, 'timeout' => 15])->get($url);

            $result = $response->json() ?? ['body' => $response->body(), 'status' => $response->status()];
            $isSuccess = $response->successful() && (!isset($result['type']) || $result['type'] === 'success');
            
            $status = $isSuccess ? 'success' : 'failed';
            $this->createLog($formattedMobile, $message, $type, $status, $result);

            Log::info("SMS Gateway Response for {$formattedMobile}:", ['response' => $result]);

            return $result;

        } catch (\Exception $e) {
            Log::error("SMS Gateway Request Exception for {$formattedMobile}: " . $e->getMessage());
            $this->createLog($formattedMobile, $message, $type, 'failed', ['error' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    protected function createLog($mobile, $message, $type, $status, $response)
    {
        try {
            SmsLog::create([
                'mobile' => $mobile,
                'message' => $message,
                'type' => $type,
                'status' => $status,
                'gateway_response' => is_array($response) ? $response : ['raw' => (string)$response],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to save SMS log: " . $e->getMessage());
        }
    }
}
