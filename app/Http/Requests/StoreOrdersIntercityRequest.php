<?php

namespace App\Http\Requests;

use App\Models\OrdersIntercity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrdersIntercityRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('orders_intercity_create'),
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
            'comments' => [
                'string',
                'nullable',
            ],
            'destination_city' => [
                'string',
                'nullable',
            ],
            'destination_location_lat_lng' => [
                'string',
                'nullable',
            ],
            'destination_location_name' => [
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
            'driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'final_rate' => [
                'string',
                'nullable',
            ],
            'intercity_service' => [
                'string',
                'nullable',
            ],
            'intercityservice_id' => [
                'integer',
                'exists:intercity_services,id',
                'nullable',
            ],
            'number_of_passenger' => [
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
            'parcel_dimension' => [
                'string',
                'nullable',
            ],
            'parcel_image' => [
                'string',
                'nullable',
            ],
            'parcel_weight' => [
                'string',
                'nullable',
            ],
            'payment_status' => [
                'string',
                'nullable',
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
            'source_city' => [
                'string',
                'nullable',
            ],
            'source_location_lat_lng' => [
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
            'user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'when_dates' => [
                'string',
                'nullable',
            ],
            'when_time' => [
                'string',
                'nullable',
            ],
        ];
    }
}
