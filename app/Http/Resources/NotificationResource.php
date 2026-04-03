<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{


    public function toArray(Request $request): array
    {

        return [
            'id'            => $this->id,
            'title'         => $this->title??'',
            'body'          => $this->body??'',
            'redirect'      => $this->redirect??''
        ];
    }
}
