<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{

    public function authorize(): bool
    {
       
        return true; 
    }
    public function rules(): array
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id'    => ['required', 'exists:gallery_categories,id'],
        ];
        
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }
}