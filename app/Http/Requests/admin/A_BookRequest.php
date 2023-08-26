<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class A_BookRequest extends FormRequest
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
            'customer_id' => 'required|integer',
            'court_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'required|integer',
            'start_time' => 'required|string',
            'payment_mode' => 'required|integer',
        ];
    }
}
