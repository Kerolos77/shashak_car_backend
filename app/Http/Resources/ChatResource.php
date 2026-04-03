<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{


    public function toArray(Request $request): array
    {

        return   [
            'message_id'  => $this->id,
            'sender_id'   => $this->sender_id,
            'message'     => $this->message,
        ];
    }
}
