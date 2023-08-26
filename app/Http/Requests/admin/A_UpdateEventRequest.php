<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class A_UpdateEventRequest extends FormRequest
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
            'title' => 'sometimes|string|max:100',
            'court_id' => 'sometimes|integer|exists:courts,id',
            'start_time' => 'sometimes|string',
            'end_time' => 'sometimes|string',
        ];
    }
}
