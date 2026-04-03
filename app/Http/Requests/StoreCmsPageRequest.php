<?php

namespace App\Http\Requests;

use App\Models\CmsPage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCmsPageRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('cms_page_create'),
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
                'nullable',
            ],
            'publish' => [
                'boolean',
            ],
            'slug' => [
                'string',
                'nullable',
            ],
            'description' => [
                'string',
                'nullable',
            ],
        ];
    }
}
