<?php

namespace App\Http\Requests;

use App\Models\ReviewDriver;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreReviewDriverRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('review_driver_create'),
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
            'comment' => [
                'string',
                'nullable',
            ],
            'customer_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'rating' => [
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
