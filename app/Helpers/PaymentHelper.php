<?php

namespace App\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Nafezly\Payments\Classes\PaymobPayment;
trait PaymentHelper
{


    public function tap($amount)
    {
        $userID = $this->getUserIDByToken(request()->bearerToken());
        $user = User::find($userID);
        $payment = new PaymobPayment();
        $res =$payment->setUserId($user->id)
        ->setUserFirstName($user->full_name)
        ->setUserLastName($user->full_name)
        ->setUserEmail($user->email)
        ->setUserPhone($user->phone_number)
        //->setCurrency($currency)
        ->setAmount($amount)
        ->pay();

        return $res;
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

}
