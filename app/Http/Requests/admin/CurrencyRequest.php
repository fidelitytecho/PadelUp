<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'currency.name_en' => 'required|string',
            'currency.name_ar' => 'sometimes|string',
            'currency.sign_en' => 'required|string',
            'currency.sign_ar' => 'sometimes|string',
        ];
    }
}
