<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Traits\MapsProcessing;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
{


    public function toArray(Request $request): array
    {



        return [
            'name'          => $this->name,
            'number'        => $this->number,
            'exp_month'     => $this->exp_month,
            'exp_year'      => $this->exp_year,
            'cvc'           => $this->cvc,
            'is_default'    => $this->is_default,



        ];
    }
}
