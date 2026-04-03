<?php

namespace App\Http\Requests;

use App\Models\FreightVehicle;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFreightVehicleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('freight_vehicle_create'),
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
            'name' => [
                'string',
                'required',
            ],
            'description' => [
                'string',
                'nullable',
            ],
            'enable' => [
                'boolean',
                'nullable',
            ],
            'height' => [
                'numeric',
                'nullable',
            ],
            'width' => [
                'numeric',
                'nullable',
            ],
            'km_charge' => [
                'numeric',
                'nullable',
            ],
            'images.*' => [
                'image',
                'nullable',
            ],
        ];
    }
}
