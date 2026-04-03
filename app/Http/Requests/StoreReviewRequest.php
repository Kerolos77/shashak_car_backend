<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'التقييم مطلوب',
            'rating.integer' => 'التقييم يجب أن يكون رقم',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1',
            'rating.max' => 'التقييم يجب أن يكون على الأكثر 5',
            'comment.max' => 'التعليق لا يمكن أن يزيد عن 1000 حرف',
        ];
    }
}
