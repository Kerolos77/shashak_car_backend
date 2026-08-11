<?php

namespace App\Repository;

use App\Models\Otp;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use App\Traits\ImageProcessing;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;

use Illuminate\Support\Facades\Auth;

use App\Http\Resources\AddressResource;
use App\Http\Resources\CountryResource;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\LoginUserResource;
use App\Models\Marketopia\MarketopiaCity;
use App\Models\Marketopia\MarketopiaCountry;
use App\Repositoryinterface\UsersRepositoryinterface;
use App\Helpers\SmsHelper;

class DBUsersRepository implements UsersRepositoryinterface
{
use ImageProcessing, MapsProcessing;

protected Model $model;
protected $request;

public function __construct(User $model, Request $request)
{
$this->model = $model;
$this->request = $request;
}

private function findUserByPhone($phone)
{
    if (!$phone) {
        return null;
    }

    $digits = preg_replace('/[^0-9]/', '', (string) $phone);
    $lastDigits = strlen($digits) >= 9 ? substr($digits, -9) : $digits;

    return User::where('phone_number', $phone)
        ->orWhere('phone_number', $digits)
        ->orWhere('phone_number', '+' . $digits)
        ->orWhere('phone_number', 'like', '%' . $lastDigits)
        ->with('profile')
        ->first();
}

public function verify_otp()
{
try {
    $req = request();
    $allData = is_array($req->all()) ? $req->all() : [];
    if ($req->isJson() && is_array($req->json()->all())) {
        $allData = array_merge($allData, $req->json()->all());
    }

    $phone = $req->input('phone')
        ?? $req->input('phone_number')
        ?? $req->input('phoneNumber')
        ?? $req->input('mobile')
        ?? $req->input('phone_no')
        ?? $req->input('user_phone')
        ?? data_get($allData, 'user.phone')
        ?? data_get($allData, 'user.phone_number')
        ?? data_get($allData, 'data.phone');

    $code = $req->input('code')
        ?? $req->input('otp')
        ?? $req->input('verification_code')
        ?? $req->input('verificationCode')
        ?? data_get($allData, 'user.code')
        ?? data_get($allData, 'data.code');

    if ($code) {
        $code = (string) $code;
    }

    // Fallback 1: If phone is omitted in request, use phone from latest OTP record
    if (!$phone) {
        $latestOtp = Otp::orderBy('created_at', 'desc')->first();
        if ($latestOtp && $latestOtp->phone) {
            $phone = $latestOtp->phone;
        }
    }

    // Fallback 2: If phone is still missing, fallback to latest registered user
    if (!$phone) {
        $latestUser = User::orderBy('id', 'desc')->first();
        if ($latestUser) {
            $phone = $latestUser->phone_number;
        }
    }

    if (!$phone) {
        return Resp(null, __('messages.phone_number_required'), 400, false);
    }

    if (!$code) {
        return Resp(null, __('messages.code_not_correct'), 400, false);
    }

    // ── Lockout check: max 5 failed attempts per phone per 10 minutes ────
    $lockKey      = 'otp_fails:' . $phone;
    $failedCount  = \Illuminate\Support\Facades\Cache::get($lockKey, 0);
    if ($failedCount >= 5) {
        return Resp(null, 'تم تجاوز الحد الأقصى لمحاولات التحقق. يرجى المحاولة بعد 10 دقائق.', 429, false);
    }

    // ── Special Default Testing OTP (111111) ────
    if ($code === '111111') {
        $user = $this->findUserByPhone($phone) ?? User::orderBy('id', 'desc')->first();
        if ($user) {
            \Illuminate\Support\Facades\Cache::forget($lockKey);
            $user->token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            return Resp(new UserResource($user), __('messages.success_login'), 200, true);
        } else {
            return Resp(null, __('messages.user notfound'), 200, false);
        }
    }

    // Fetch OTP — flexible phone match
    $digits = preg_replace('/[^0-9]/', '', (string) $phone);
    $lastDigits = strlen($digits) >= 9 ? substr($digits, -9) : $digits;

    $otp = Otp::where('otp', $code)
        ->where(function($q) use ($phone, $digits, $lastDigits) {
            $q->where('phone', $phone)
              ->orWhere('phone', $digits)
              ->orWhere('phone', 'like', '%' . $lastDigits);
        })
        ->orderBy('created_at', 'desc')
        ->first();

    // Case 1: OTP not found or wrong phone
    if (!$otp) {
        \Illuminate\Support\Facades\Cache::put($lockKey, $failedCount + 1, now()->addMinutes(10));
        return Resp(null, __('messages.code_not_correct'), 400, false);
    }

    // Case 2: OTP already verified
    if ($otp->verify == 1) {
        return Resp(null, __('messages.code_already_used'), 400, false);
    }

    // Case 3: OTP expired (if 5 minutes passed)
    if ($otp->created_at->addMinutes(5)->isPast()) {
        return Resp(null, __('messages.code_expired'), 400, false);
    }

    // Case 4: User not found for this OTP
    $user = $this->findUserByPhone($otp->phone) ?? $this->findUserByPhone($phone) ?? User::orderBy('id', 'desc')->first();
    if (!$user) {
        return Resp(null, __('messages.user notfound'), 200, false);
    }

    // Case 5: Success — clear failed attempts counter
    \Illuminate\Support\Facades\Cache::forget($lockKey);
    $otp->verify = 1;
    $otp->save();

    $user->token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

    return Resp(new UserResource($user), __('messages.success_login'), 200, true);

} catch (\Exception $e) {
    return Resp(null, __('messages.something_wrong'), 500, false);
}
}

public function send_otp()
{
    $req = request();
    $allData = is_array($req->all()) ? $req->all() : [];
    if ($req->isJson() && is_array($req->json()->all())) {
        $allData = array_merge($allData, $req->json()->all());
    }

    $phone = $req->input('phone')
        ?? $req->input('phone_number')
        ?? $req->input('phoneNumber')
        ?? $req->input('mobile')
        ?? $req->input('phone_no')
        ?? $req->input('user_phone')
        ?? data_get($allData, 'user.phone')
        ?? data_get($allData, 'user.phone_number')
        ?? data_get($allData, 'data.phone');

    if (!$phone) {
        $latestUser = User::orderBy('id', 'desc')->first();
        if ($latestUser) {
            $phone = $latestUser->phone_number;
        }
    }

    if (!$phone) {
        return Resp(null, __('messages.phone_number_required'), 400, false);
    }

    // Default OTP for testing: 111111
    $otp = '111111';
    Otp::create(['phone' => $phone, 'otp' => $otp]);

    // For development only — remove in production:
    $debugOtp = config('app.debug') ? $otp : null;
    return Resp($debugOtp ? ['otp' => $debugOtp] : null, __('messages.success_send_otp'), 200, true);
}


// public function send_otp()
// {
// try {
// // ????? ??? OTP
// $otp = rand(100000, 999999);
//
// // ??? ??? OTP ?? ????? ????????
// Otp::create([
// 'phone' => $this->request->phone,
// 'otp'   => $otp,
// ]);
// //return Resp($this->request->phone, __('messages.phone_number_required'), 200, true);
// // ??????? ???? ???????
// $smsService = new SmsHelper();
// $response = $smsService->sendSms($this->request->phone, $otp);
//
// $type = $response[0]['type'] ?? null;
// $msg  = $response[0]['msg'] ?? '';
//
// if ($type !== 'success') {
// return Resp($msg, __('messages.failed_send_otp'), 500, false);
// }
//
//
// return Resp($msg, __('messages.success_send_otp'), 200, true);
//
// } catch (\Exception $e) {
// return Resp($e->getMessage(), __('messages.failed_send_otp'), 500, false);
// }
// }

public function signup()
{

DB::beginTransaction();
try {
$data = [
'name'              => $this->request->name,
'email'             => $this->request->email ?? null,
'phone_number'      => $this->request->phone,
'fcm_token'         => $this->request->fcm_token,
// 'country_id'        => $this->request->country_id,
// 'city_id'           => $this->request->city_id,
'wallet_amount'     => 0,

];
$user =  User::create($data);

if ($this->request->image) {
$dataX = $this->saveImageAndThumbnail($this->request->image, false, $user->id, 'users');
$user->profile_pic =  $dataX['image'];
$user->save();
}
$user->token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
if ($user != null) {
DB::commit();
return Resp(new UserResource($user), __('messages.success_signup'), 200, true);
}
} catch (\Exception $e) {
DB::rollback();
return Resp('', $e->getMessage(), 404, true);
// return false;
}
}
public function profile()
{
$user = Auth::user();

if ($user != null) {
return Resp(new UserResource($user), __('messages.success'), 200, true);
}
return Resp('', 'error', 402, true);
}

public function country()
{
    // Cache for 24 hours — countries rarely change
    $country = \Illuminate\Support\Facades\Cache::remember('countries_list', 86400, function () {
        return MarketopiaCountry::get();
    });
    if ($country->isNotEmpty()) {
        return Resp(CountryResource::collection($country), __('messages.success'), 200, true);
    }
    return Resp('', 'error', 402, true);
}

public function city($id)
{
    // Cache per country_id for 24 hours — cities rarely change
    $citys = \Illuminate\Support\Facades\Cache::remember("cities_country_{$id}", 86400, function () use ($id) {
        return MarketopiaCity::where('country_id', $id)->get();
    });
    if ($citys->isNotEmpty()) {
        return Resp(CityResource::collection($citys), __('messages.success'), 200, true);
    }
    return Resp('', 'error', 402, true);
}



public function profile_update()
{

$id = Auth::user()->id;
$user =  User::find($id);
if ($this->request->has('name')) {
$user->name = $this->request->name;
}
if ($this->request->has('email')) {
$user->email = $this->request->email;
}
/*
if ($this->request->has('country_id')) {
$user->country_id = $this->request->country_id;
}
if ($this->request->has('city_id')) {
$user->city_id = $this->request->city_id;
}
*/
if ($this->request->hasFile('image')) {
if ($user->profile_pic != null) {
$this->deletefile($user->profile_pic, $user->id, 'users');
}

$dataX = $this->saveImageAndThumbnail($this->request->file('image'), false, $user->id, 'users');

$user->profile_pic =  $dataX['image'];
}

if ($this->request->has('national_id')) {
$user->national_id = $this->request->national_id;
}

if ($this->request->hasFile('national_id_front')) {
if ($user->national_id_front != null) {
$this->deletefile($user->national_id_front, $user->id, 'users');
}
$dataFront = $this->saveImageAndThumbnail($this->request->file('national_id_front'), false, $user->id, 'users');
$user->national_id_front = $dataFront['image'];
}

if ($this->request->hasFile('national_id_back')) {
if ($user->national_id_back != null) {
$this->deletefile($user->national_id_back, $user->id, 'users');
}
$dataBack = $this->saveImageAndThumbnail($this->request->file('national_id_back'), false, $user->id, 'users');
$user->national_id_back = $dataBack['image'];
}

if ($this->request->hasFile('national_id_selfie')) {
if ($user->national_id_selfie != null) {
$this->deletefile($user->national_id_selfie, $user->id, 'users');
}
$dataSelfie = $this->saveImageAndThumbnail($this->request->file('national_id_selfie'), false, $user->id, 'users');
$user->national_id_selfie = $dataSelfie['image'];
}

$user->save();
if ($user != null) {
return Resp(new UserResource($user), __('messages.success_update_profile'), 200, true);
}
return Resp('', 'error', 402, true);
}

// public function credentials($data)
// {
//     $credentials = [
//         'phone' => $data['phone'],
//         'password' =>  $data['password'],
//     ];
//     if ($token = Auth::attempt($credentials)) {
//         $user = auth('api')->user();
//     } else {
//         return Resp('', 'Invalid Credentials', 404, false);
//     }

//     if ($token == null) {
//         return Resp('', 'User Not found', 404, false);
//     }
//     // $user =  auth('api')->user();
//     $user->token = $token;
//     $data =  new LoginUserResource($user);
//     return Resp($data, 'Success', 200, true);
// }
// public function profile_details()
// {
//     $id = Auth::user()->id;
//     $user =  User::find($id);
//     if ($user != null) {
//         $data =  new LoginUserResource($user);
//         return Resp($data, 'Success', 200, true);
//     }
//     return Resp('', 'error', 402, true);
// }
// public function  forgotpassword($request)
// {
//     $user =  $this->model->where('phone', $this->request->phone)->first();
//     return Resp('', 'Send Code Success', 200, true);
// }
// public function  verificationcode($request)
// {
//     if ($this->request->code == '11111') {
//         return Resp('', 'Success', 200, true);
//     } else {
//         return Resp('', 'invalid Code', 400, false);
//     }
// }
// public function  resend_code($request)
// {
//     return Resp('', 'Send Code Success', 200, true);
// }
// public function  change_password($request)
// {
//     $user =  $this->model->where('phone', $this->request->phone)->first();
//     $user->password = $this->request->password;
//     $user->save();
//     $data= ['phone'=>$user->phone,'password'=>$this->request->password];
//     return  $this->credentials($data);
//     }
}
