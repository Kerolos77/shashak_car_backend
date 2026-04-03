<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class DriverRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => [
               'integer',
                'nullable',
            ],
            'offer' => [
                'string',
                'nullable',
            ],
            'coupon_id' => [
                'string',
                'nullable',
            ],
            'distance' => [
                'string',
                'nullable',
            ],
            'distance_type' => [
                'string',
                'nullable',
            ],
            'payment_id' => [
                'string',
                'nullable',
            ],
            'destination_name' => [
                'string',
                'nullable',
            ],
            'destination_lat' => [
                'string',
                'nullable',
            ],
            'destination_long' => [
                'string',
                'nullable',
            ],
            'source_name' => [
                'boolean',
            ],
            'source_lat' => [
                'string',
                'nullable',
            ],
            'source_long' => [
                'string',
                'nullable',
            ],
            'offer_rate' => [
                'string',
                'nullable',
            ],
            'final_rate' => [
                'string',
                'nullable',
            ],

        ];
    }
}
