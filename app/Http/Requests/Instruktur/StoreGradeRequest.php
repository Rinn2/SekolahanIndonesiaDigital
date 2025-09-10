<?php
// app/Http/Requests/Instruktur/StoreGradeRequest.php

namespace App\Http\Requests\Instruktur;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'instruktur';
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => 'required|integer|exists:enrollments,id',
            'meeting_number' => 'required|integer|min:1',
            'grade' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'ID pendaftaran wajib diisi.',
            'enrollment_id.integer' => 'ID pendaftaran harus berupa angka.',
            'enrollment_id.exists' => 'Pendaftaran tidak ditemukan.',
            'meeting_number.required' => 'Nomor pertemuan wajib diisi.',
            'meeting_number.integer' => 'Nomor pertemuan harus berupa angka.',
            'meeting_number.min' => 'Nomor pertemuan minimal 1.',
            'grade.required' => 'Nilai wajib diisi.',
            'grade.numeric' => 'Nilai harus berupa angka.',
            'grade.min' => 'Nilai minimal 0.',
            'grade.max' => 'Nilai maksimal 100.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}