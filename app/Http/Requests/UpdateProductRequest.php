<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:products,name,'.$this->product->id,
            'price' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
            'category' => 'sometimes|string|max:255',
            'image' => 'sometimes|nullable|url',
        ];
    }
}
