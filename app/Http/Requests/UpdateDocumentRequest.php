<?php

namespace App\Http\Requests;

use App\Models\Document;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('document_edit'),
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
            'back_side' => [
                'boolean',
            ],
            'enable' => [
                'boolean',
            ],
            'expire_at' => [
                'boolean',
            ],
            'front_side' => [
                'boolean',
            ],
            'title' => [
                'string',
                'nullable',
            ],
            'is_deleted' => [
                'boolean',
            ],
        ];
    }
}
