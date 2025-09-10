<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nik' => 'nullable|string|size:16|unique:users,nik|regex:/^[0-9]{16}$/', // Changed from max:16 to size:16
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,instruktur,peserta'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 2 karakter',
            'name.max' => 'Nama maksimal 255 karakter',

            'nik.size' => 'NIK harus tepat 16 digit', // Updated message
            'nik.unique' => 'NIK sudah terdaftar',
            'nik.regex' => 'NIK harus berupa 16 digit angka',

            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',

            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',

            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid (admin, instruktur, peserta)'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalisasi email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Normalisasi NIK - hapus spasi dan karakter non-digit, tapi hanya jika ada value
        if ($this->has('nik') && !is_null($this->nik) && trim($this->nik) !== '') {
            $cleanNik = preg_replace('/[^0-9]/', '', trim($this->nik));
            $this->merge([
                'nik' => $cleanNik ?: null // Set ke null jika kosong setelah cleaning
            ]);
        } else {
            // Jika NIK tidak ada atau kosong, set ke null
            $this->merge(['nik' => null]);
        }
    }

    /**
     * Get validated data with proper NIK handling
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Pastikan NIK yang kosong diubah ke null untuk database
        if (isset($validated['nik']) && empty($validated['nik'])) {
            $validated['nik'] = null;
        }
        
        return $validated;
    }
}