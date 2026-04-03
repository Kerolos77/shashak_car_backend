<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'from_user_id'  => $this->from_user_id,
            'to_user_id'    => $this->to_user_id,
            'order_id'      => $this->order_id,
            'reviewer'      => $this->reviewer,
            'rating'        => $this->rating ?? 0,
            'comment'       => $this->comment ?? '',
            'created_at'    => $this->created_at ?? '',
            'from_user'     => $this->fromUser ? new UserResource($this->fromUser) : null,
            'to_user'       => $this->toUser ? new UserResource($this->toUser) : null,
        ];
    }
}
