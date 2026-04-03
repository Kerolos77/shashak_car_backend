<?php

namespace App\Http\Requests;

use App\Models\VehicleType;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateVehicleTypeRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('vehicle_type_edit'),
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
            'enable' => [
                'boolean',
            ],
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
