<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRelationRequest extends FormRequest
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
            // 'image.category_id' => 'sometimes',
            'image.image_url' => 'sometimes|image',
            'image.thumbnail_url' => 'sometimes|image',
            'review.customer_id' => 'sometimes',
            'review.rate' => 'sometimes',
            'review.comment' => 'sometimes',
        ];
    }
}
