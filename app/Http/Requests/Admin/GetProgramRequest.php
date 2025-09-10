<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GetProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:programs,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Ambil id dari route dan masukkan ke data yang akan divalidasi
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'ID program harus diisi.',
            'id.integer' => 'ID program harus berupa angka.',
            'id.exists' => 'Program tidak ditemukan.',
        ];
    }
}
