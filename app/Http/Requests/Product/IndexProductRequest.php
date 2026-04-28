<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id'),
            ],
            'price_min' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'price_max' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:price_min',
            ],
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],
            'sort_by' => [
                'nullable',
                Rule::in(['price', 'created_at']),
            ],
            'sort_direction' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }
}