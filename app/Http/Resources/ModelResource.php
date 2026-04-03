<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelResource extends JsonResource
{


    public function toArray(Request $request): array
    {
        return [

            'id'              => $this->id??'',
            'title '          => $this->title??'',
        ];
    }
}
