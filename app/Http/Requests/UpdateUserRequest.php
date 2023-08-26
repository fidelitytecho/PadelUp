<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'sometimes|string|max:20',
            'last_name' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:50|unique:users,email',
            'password' => 'sometimes|string|min:8',
        ];
    }
}
