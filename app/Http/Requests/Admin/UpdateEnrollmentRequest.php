<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,diterima,ditolak,lulus,dropout',
            'notes' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid'
        ];
    }
}