<?php

namespace App\Http\Requests;

use App\Models\Airport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAirportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('airport_edit'),
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
            'airport_lat' => [
                'string',
                'nullable',
            ],
            'airport_lng' => [
                'string',
                'nullable',
            ],
            'airport_name' => [
                'string',
                'nullable',
            ],
            'city_location' => [
                'string',
                'nullable',
            ],
            'country' => [
                'string',
                'nullable',
            ],
            'state' => [
                'string',
                'nullable',
            ],
        ];
    }
}
