<?php

namespace App\Http\Requests;

use App\Models\Language;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLanguageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('language_create'),
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
            'code' => [
                'string',
                'nullable',
            ],
            'is_deleted' => [
                'boolean',
            ],
            'is_rtl' => [
                'boolean',
            ],
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
