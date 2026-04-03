<?php

namespace App\Http\Requests;

use App\Models\OnBoarding;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOnBoardingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('on_boarding_create'),
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
            'image' => [
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
