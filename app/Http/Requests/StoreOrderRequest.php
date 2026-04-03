<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrderRequest extends FormRequest
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
            'payment_type' => [
                'string',
                'nullable',
                'in:cash,wallet,card,saved_card,wallet_card,wallet_cash',
            ],
            'saved_card_id' => [
                'integer',
                'nullable',
            ],
        ];
    }
}
