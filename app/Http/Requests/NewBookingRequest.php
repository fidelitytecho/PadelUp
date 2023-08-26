<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewBookingRequest extends FormRequest
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
            'court_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'required|integer',
            'start_time' => 'required|string',
            'payment_mode' => 'required|integer',
        ];
    }
}
