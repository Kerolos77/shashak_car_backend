<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Http\Resources\CreditResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResourceCollection extends JsonResource
{


    public function toArray(Request $request): array
    {



        return [
            'wallet'   => $this['wallet'],
            'credits'  => CreditResource::collection($this['credit']),




        ];
    }
}
