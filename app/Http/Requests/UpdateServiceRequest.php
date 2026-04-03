<?php

namespace App\Http\Requests;

use App\Models\Service;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('service_edit'),
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
            'admin_commission' => [
                'string',
                'nullable',
            ],
            'enable' => [
                'boolean',
            ],
            'image' => [
                'string',
                'nullable',
            ],
            'intercity_type' => [
                'boolean',
            ],
            'km_charge' => [
                'string',
                'nullable',
            ],
            'offer_rate' => [
                'string',
                'nullable',
            ],
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
