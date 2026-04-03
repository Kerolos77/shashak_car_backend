<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCouponRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('coupon_create'),
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
            'amount' => [
                'string',
                'required',
            ],
            'code' => [
                'string',
                'required',
            ],
            'is_public' => [
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
