<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'], // beda di sini → opsional
            'category_id' => ['required', 'exists:gallery_categories,id'],
        ];
    }
}
