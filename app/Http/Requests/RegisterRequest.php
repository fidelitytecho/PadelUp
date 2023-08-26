<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'user.email' => 'required|email|max:50|unique:users,email',
            'user.mobile' => 'required_without:user.email',
            'user.full_mobile' => 'required_without:user.email',
            'user.dial_code' => 'required_without:user.email',
            'user.password' => 'required|string|min:8',
            'user.username' => 'nullable|string|max:20',
            'user.skill_level' => 'sometimes|string',
            'user.gender' => 'sometimes|string',
            'user.birthday' => 'nullable|after:01-01-1900|date_format:d-m-Y',
            'user.image' => 'nullable|image',
            'user.is_signed_up' => 'required|bool',
            'fcmToken' => 'sometimes|string'
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
