<?php

namespace App\Http\Requests;

use App\Models\Service;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreServiceRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('service_create'),
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
                'numeric',
                'nullable',
            ],
            'images' => [
                'array'
            ],
            'images.*' => [
                'image',
                'mimes:png,jpg,webp,jpeg',
                'required'
            ],
            'km_charge' => [
                'string',
                'nullable',
            ],
            'title' => [
                'string',
                'required',
                'max:255'
            ],
        ];
    }
}
