<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'total_meetings' => 'required|integer|min:1',
            'level' => 'required|in:Pemula,Menengah,Lanjutan',
            'max_participants' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama program harus diisi',
            'duration_months.required' => 'Durasi program harus diisi',
            'duration_months.min' => 'Durasi minimal 1 bulan',
            'level.required' => 'Level harus dipilih',
            'total_meetings.required' => 'Total pertemuan harus diisi',
            'total_meetings.min' => 'Total pertemuan minimal 1',
            'level.in' => 'Level tidak valid',
            'max_participants.required' => 'Maksimal peserta harus diisi',
            'max_participants.min' => 'Maksimal peserta minimal 1',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif'
        ];
    }
}