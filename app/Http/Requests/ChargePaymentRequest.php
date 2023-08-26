<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargePaymentRequest extends FormRequest
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
            'bookingID' => 'required|integer|exists:bookings,id',
            'total' => 'required',
            'fees' => 'required|array|min:1',
            '*.fees.*.title' => 'required|string',
            '*.fees.*.amount' => 'required',
            '*.fees.*.isDiscount' => 'required|boolean',
            'payment_type' => 'required|integer'
        ];
    }
}
