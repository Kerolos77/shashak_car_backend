<?php

namespace App\Http\Requests;

use App\Models\FreightVehicle;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFreightVehicleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('freight_vehicle_edit'),
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
            'description' => [
                'string',
                'nullable',
            ],
            'enable' => [
                'boolean',
            ],
            'height' => [
                'string',
                'nullable',
            ],
            'image' => [
                'string',
                'nullable',
            ],
            'km_charge' => [
                'string',
                'nullable',
            ],
            'length' => [
                'string',
                'nullable',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'width' => [
                'string',
                'nullable',
            ],
        ];
    }
}
