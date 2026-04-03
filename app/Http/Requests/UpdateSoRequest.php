<?php

namespace App\Http\Requests;

use App\Models\So;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('so_edit'),
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
            'order_id' => [
                'integer',
                'exists:orders,id',
                'nullable',
            ],
            'order_type' => [
                'string',
                'nullable',
            ],
            'status' => [
                'string',
                'nullable',
            ],
        ];
    }
}
