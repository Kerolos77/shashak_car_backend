<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUp extends FormRequest
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
            'name'  => 'required',
            'phone' => 'required|unique:users,phone',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
            'accept_rule' => 'required|boolean'
        ];
    }
}
