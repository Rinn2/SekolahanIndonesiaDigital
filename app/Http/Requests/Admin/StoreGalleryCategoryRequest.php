<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Route sudah dilindungi middleware 'admin', jadi return true sudah aman.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:gallery_categories,name',
            'description' => 'nullable|string|max:1000',
        ];
    }
}