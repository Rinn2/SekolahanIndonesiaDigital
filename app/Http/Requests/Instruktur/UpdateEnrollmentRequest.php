<?php

namespace App\Http\Requests\Instruktur;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'instruktur';
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['pending', 'diterima', 'ditolak', 'lulus', 'dropout'])
            ],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status harus salah satu dari: pending, diterima, ditolak, lulus, dropout',
            'notes.max' => 'Catatan maksimal 1000 karakter',
        ];
    }
}
