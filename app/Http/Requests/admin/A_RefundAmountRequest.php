<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class A_RefundAmountRequest extends FormRequest
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
            'payment_id' => 'required|integer|exists:payments,id',
            'refund_data.refunded' => 'required',
        ];
    }
}
