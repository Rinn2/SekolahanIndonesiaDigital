<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mengambil ID kategori dari route, contoh: /admin/categories/{category}
        $categoryId = $this->route('category')->id;

        return [
            // Aturan unique akan mengabaikan data dengan ID saat ini
            'name' => 'required|string|max:255|unique:gallery_categories,name,' . $categoryId,
            'description' => 'nullable|string|max:1000',
        ];
    }
}