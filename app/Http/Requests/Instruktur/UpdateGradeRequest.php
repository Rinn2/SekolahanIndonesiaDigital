<?php
// app/Http/Requests/Instruktur/UpdateGradeRequest.php

namespace App\Http\Requests\Instruktur;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'instruktur';
    }

    public function rules(): array
    {
        return [
            'grade' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'grade.required' => 'Nilai wajib diisi.',
            'grade.numeric' => 'Nilai harus berupa angka.',
            'grade.min' => 'Nilai minimal 0.',
            'grade.max' => 'Nilai maksimal 100.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}