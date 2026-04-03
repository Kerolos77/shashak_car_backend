<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('order_edit'),
            response()->json(
                ['message' => 'This action is unauthorized.'],
                Response::HTTP_FORBIDDEN
            ),
        );

        return true;
    }

    public function rules(): array
    {
        return [
            'accepted_driver' => [
                'string',
                'nullable',
            ],
            'admin_commission' => [
                'string',
                'nullable',
            ],
            'destination_location_name' => [
                'string',
                'nullable',
            ],
            'destination_location_l_at_lng' => [
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
            'driver' => [
                'string',
                'nullable',
            ],
            'final_rate' => [
                'string',
                'nullable',
            ],
            'offer_rate' => [
                'string',
                'nullable',
            ],
            'otp' => [
                'string',
                'nullable',
            ],
            'payment_status' => [
                'boolean',
            ],
            'payment_type' => [
                'string',
                'nullable',
            ],
            'position' => [
                'string',
                'nullable',
            ],
            'rejected_driver' => [
                'string',
                'nullable',
            ],
            'service' => [
                'string',
                'nullable',
            ],
            'source_location_l_at_lng' => [
                'string',
                'nullable',
            ],
            'source_location_name' => [
                'string',
                'nullable',
            ],
            'status' => [
                'string',
                'nullable',
            ],
            'tax_list' => [
                'string',
                'nullable',
            ],
            'update_date' => [
                'string',
                'nullable',
            ],
            'user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
        ];
    }
}
