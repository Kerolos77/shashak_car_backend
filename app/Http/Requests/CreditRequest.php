<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'number' => 'required|numeric|digits:16|unique:user_credits,number', // Assuming a 16-digit credit card number
            'exp_month' => 'required|numeric|min:1|max:12', // Month should be between 1 and 12
            'exp_year' => 'required|numeric|min:' . date('y') . '|max:' . (date('y') + 10), // Assuming the minimum year is the current year and maximum is 10 years ahead
            'cvc' => 'required|numeric|digits_between:3,4', // Assuming CVC is 3 or 4 digits
        ];
    }
}
