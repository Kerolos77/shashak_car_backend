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
        return Resp(Setting::first(), 'success');
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
