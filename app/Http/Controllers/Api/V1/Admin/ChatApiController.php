<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use Laravel\Sanctum\PersonalAccessToken;


class ChatApiController extends Controller
{
    use ResponseHelper;
   
        public function getUserIDByToken($hashedToken)
        {
            $token = PersonalAccessToken::findToken($hashedToken);
            if($token != null) {
                return $token->tokenable_id;
    
            } else {
                return false;
            }
    
        }
    public function send_message(Request $request)
    {
        $room = room::where(['trip_id' =>  $request->trip_id])->first();
        if ($room  == null) {
            $room = room::create(['trip_id' =>  $request->trip_id]);
        }
        $userID = $this->getUserIDByToken(request()->bearerToken());
        $conversion = [
            'sender_id'     => $userID,
            'message'       => $request->message,
            'receiver_id'   => 2,
            'room_id'       => $room->id,
        ];
        $conversion = Chat::create($conversion);
        event(new MessageSent($conversion,  $request->trip_id));

        return $this->apiResponseHandler(200, true, __('request.data_retrieved'));
    }
}
