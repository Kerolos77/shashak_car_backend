<?php

namespace App\Http\Requests;

use App\Models\DriverDocument;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDriverDocumentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('driver_document_edit'),
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
            'documents' => [
                'string',
                'nullable',
            ],
        ];
    }
}
