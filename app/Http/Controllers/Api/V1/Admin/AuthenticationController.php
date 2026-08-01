<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\UserDocsResource;
use App\Models\Caption;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\SignUp;
use App\Models\PaymentMethod;
use App\Helpers\PaymentHelper;
use App\Traits\MapsProcessing;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\AddAddressRequest;
use App\Repositoryinterface\UsersRepositoryinterface;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    use PaymentHelper;
    private $userRepositry;
    public function __construct(UsersRepositoryinterface $userRepositry)
    {
        $this->userRepositry = $userRepositry;
    }
    public function settings()
    {
        $setting = Setting::first();
        if (!$setting) {
            return Resp(null, 'No settings found', 404, false);
        }

        $data = [
            'id' => $setting->id,
            // Feature Toggles (ON / OFF)
            'shipping_enabled' => (bool) ($setting->shipping_enabled ?? true),
            'ride_enabled' => (bool) ($setting->ride_enabled ?? true),
            'travel_enabled' => (bool) ($setting->travel_enabled ?? true),
            'intercity_enabled' => (bool) ($setting->intercity_enabled ?? true),
            'sms_enabled' => (bool) ($setting->sms_enabled ?? true),

            // Store & Social Links
            'play_store_url' => $setting->play_store_url,
            'app_store_url' => $setting->app_store_url,
            'facebook' => $setting->facebook,
            'youtube' => $setting->youtube,
            'linkedin' => $setting->linkedin,
            'twitter' => $setting->twitter,
            'tiktok' => $setting->tiktok,
            'link_1' => $setting->link_1,
            'link_2' => $setting->link_2,
            'link_3' => $setting->link_3,

            // Support & Contact
            'email_1' => $setting->email_1,
            'email_2' => $setting->email_2,
            'email_3' => $setting->email_3,
            'phone' => $setting->phone,

            // User limits & Prices
            'min_order' => (float) ($setting->min_order ?? 0),
            'min_withdraw' => (float) ($setting->min_withdraw ?? 0),
            'min_deposit' => (float) ($setting->min_deposit ?? 0),
            'referral_bonus' => (float) ($setting->referral_bonus ?? 0),
            
            // Gamification Points
            'points_user_per_trip' => (int) ($setting->points_user_per_trip ?? 0),
            'points_driver_per_trip' => (int) ($setting->points_driver_per_trip ?? 0),
        ];

        return Resp($data, 'success');
    }
    public function getUserIDByToken($hashedToken)
    {
        $token = PersonalAccessToken::findToken($hashedToken);
        if($token != null) {
            return $token->tokenable_id;

        } else {
            return false;
        }

    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8',
            'phone_number' => 'required|string|unique:users',
            // 'country_code' => 'nullable|string|max:10',
            'referral_code' => 'nullable|string|exists:users,referral_code',
            'gender' => 'nullable|in:male,female',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        // Generate Referral Code
        $referralCode = 'REF' . strtoupper(uniqid());

        // Handle Referred By
        $referredBy = null;
        if ($request->has('referral_code') && !empty($request->referral_code)) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $referredBy = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'phone_number' => $request->phone_number,
            // 'country_code' =>'',
            'referral_code' => $referralCode,
            'referred_by' => $referredBy,
            'gender' => $request->gender,
            'is_active' => 1,
        ]);
        
        // Apply Bonus if referred
        if ($referredBy) {
            $bonus = \App\Models\Setting::first()->referral_bonus ?? 0;
            if ($bonus > 0) {
                // Bonus for new user
                $user->update(['wallet_amount' => $user->wallet_amount + $bonus]);
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $bonus,
                    'type' => 'bonus',
                    'note' => 'Referral Bonus (Signup)'
                ]);

                // Bonus for referrer
                $referrer->update(['wallet_amount' => $referrer->wallet_amount + $bonus]);
                WalletTransaction::create([
                    'user_id' => $referrer->id,
                    'amount' => $bonus,
                    'type' => 'bonus',
                    'note' => 'Referral Bonus (Invited: ' . $user->name . ')'
                ]);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return Resp(['user' => $user, 'token' => $token], 'User registered successfully', 201);
    }
    public function city($id)
    {
        return $this->userRepositry->city($id);
    }
    public function country()
    {
        return $this->userRepositry->country();
    }
    public function send_otp()
    {
        return $this->userRepositry->send_otp();
    }
    public function verify_otp()
    {
        return $this->userRepositry->verify_otp();
    }
    public function profile()
    {
        return $this->userRepositry->profile();
    }
    public function profile_update()
    {
        return $this->userRepositry->profile_update();
    }
    public function toggle_online($online)
    {
        $user  = User::find(Auth::user()->id);
        $user->update(['is_online' => $online]);
        return  Resp(['is_online' => $user->is_online], 'success', 200, true);
    }

    
    public function get_docs()
    {
        $driverID = $this->getUserIDByToken(request()->bearerToken());
        $user  = User::with('profile', 'profile.car_licenses', 'profile.identity', 'profile.driver_licenses', 'profile.driver_cars')->find($driverID);
        return Resp(new UserDocsResource($user), 'success');

    }
    public function captions(Request $request)
    {
        return response()->json(Caption::with('service')->where('lang', '=', request()->header('lang'))->get());
    }
}
