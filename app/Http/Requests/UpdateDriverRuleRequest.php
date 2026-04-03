<?php

namespace App\Http\Requests;

use App\Models\DriverRule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDriverRuleRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('driver_rule_edit'),
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
            'enable' => [
                'boolean',
            ],
            'image' => [
                'string',
                'nullable',
            ],
            'is_deleted' => [
                'boolean',
            ],
            'name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
