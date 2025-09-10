<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'total_meetings' => ['required', 'integer', 'min:1'],
            'level' => ['nullable', 'string', 'max:100'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama program wajib diisi.',
            'duration_months.required' => 'Durasi program wajib diisi.',
        ];
    }
}
