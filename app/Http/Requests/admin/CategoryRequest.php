<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category.company_id' => 'required',
            'category.name_en' => 'sometimes|string',
            'category.name_ar' => 'required_without:category.name_en|string',
            'category.latitude' => 'required|string|max:10',
            'category.longitude' => 'required|string|max:10',
            'category.address' => 'required|string',
            'category.description_en' => 'sometimes|string',
            'category.description_ar' => 'required_without:category.description_en|string',
        ];
    }
}
