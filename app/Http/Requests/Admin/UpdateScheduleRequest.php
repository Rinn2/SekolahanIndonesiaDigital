<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'instructor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul jadwal harus diisi',
            'program_id.required' => 'Program harus dipilih',
            'program_id.exists' => 'Program tidak ditemukan',
            'instructor_id.exists' => 'Instruktur tidak ditemukan',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'start_date.date' => 'Format tanggal mulai tidak valid',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'end_date.date' => 'Format tanggal selesai tidak valid',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'max_participants.integer' => 'Maksimal peserta harus berupa angka',
            'max_participants.min' => 'Maksimal peserta minimal 1'
        ];
    }
}