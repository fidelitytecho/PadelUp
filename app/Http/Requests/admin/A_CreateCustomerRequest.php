<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class A_CreateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user.first_name' => 'required|string|max:20',
            'user.last_name' => 'required|string|max:20',
            'user.email' => 'sometimes|email|max:50|unique:users,email|nullable',
            'user.mobile' => 'required',
            'user.full_mobile' => 'required',
            'user.dial_code' => 'required',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'user.email.unique' => 'This email has already been used. Try using a different email.',
        ];
    }
}
