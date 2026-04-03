<?php

namespace App\Http\Requests;

use App\Models\Faq;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFaqRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('faq_create'),
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
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
