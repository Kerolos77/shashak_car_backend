<?php

namespace App\Http\Requests;

use App\Models\Tax;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTaxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('tax_create'),
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
            'country' => [
                'string',
                'nullable',
            ],
            'enable' => [
                'boolean',
            ],
            'tax' => [
                'string',
                'nullable',
            ],
            'title' => [
                'string',
                'nullable',
            ],
            'type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
