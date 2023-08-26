<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class A_CreateEventRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'court_id' => 'required|integer|exists:courts,id',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
        ];
    }
}
