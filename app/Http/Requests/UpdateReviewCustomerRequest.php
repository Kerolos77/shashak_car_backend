<?php

namespace App\Http\Requests;

use App\Models\ReviewCustomer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReviewCustomerRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('review_customer_edit'),
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
            'date' => [
                'string',
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
