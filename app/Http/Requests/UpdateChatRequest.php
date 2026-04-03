<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateChatRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('chat_edit'),
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
            'customer_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'customer_name' => [
                'string',
                'nullable',
            ],
            'customer_profile_image' => [
                'string',
                'nullable',
            ],
            'driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'driver_name' => [
                'string',
                'nullable',
            ],
            'driver_profile_image' => [
                'string',
                'nullable',
            ],
            'last_message' => [
                'string',
                'nullable',
            ],
            'last_sender' => [
                'string',
                'nullable',
            ],
            'order_id' => [
                'integer',
                'exists:orders,id',
                'nullable',
            ],
        ];
    }
}
