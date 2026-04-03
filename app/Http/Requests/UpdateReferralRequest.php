<?php

namespace App\Http\Requests;

use App\Models\Referral;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReferralRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('referral_edit'),
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
            'referral_by' => [
                'string',
                'nullable',
            ],
            'referral_code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
