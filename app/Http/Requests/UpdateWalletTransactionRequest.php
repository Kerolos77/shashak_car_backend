<?php

namespace App\Http\Requests;

use App\Models\WalletTransaction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWalletTransactionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(
            Gate::denies('wallet_transaction_edit'),
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
                'nullable',
            ],
            'note' => [
                'string',
                'nullable',
            ],
            'order_type' => [
                'string',
                'nullable',
            ],
            'payment_type' => [
                'string',
                'nullable',
            ],
            'transaction' => [
                'string',
                'nullable',
            ],
            'user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'user_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
