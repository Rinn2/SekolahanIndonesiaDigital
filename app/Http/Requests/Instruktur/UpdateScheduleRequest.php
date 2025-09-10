<?php
// app/Http/Requests/Instruktur/UpdateScheduleRequest.php

namespace App\Http\Requests\Instruktur;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'instruktur';
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul jadwal wajib diisi.',
            'title.max' => 'Judul jadwal maksimal 255 karakter.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.date' => 'Format tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'location.max' => 'Lokasi maksimal 255 karakter.',
            'max_participants.integer' => 'Jumlah maksimal peserta harus berupa angka.',
            'max_participants.min' => 'Jumlah maksimal peserta minimal 1.',
        ];
    }
}





