<?php

// app/Http/Requests/Admin/DeleteUserRequest.php
namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan user yang login adalah admin dan tidak menghapus dirinya sendiri
        $userToDelete = User::find($this->route('id'));
        
        if (!$userToDelete) {
            return false;
        }
        
        // Tidak boleh menghapus diri sendiri
        if (auth()->id() === $userToDelete->id) {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:users,id'
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'id.required' => 'ID user harus ada',
            'id.integer' => 'ID user harus berupa angka',
            'id.exists' => 'User tidak ditemukan'
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'id' => 'ID user'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ambil ID dari route parameter
        if ($this->route('id')) {
            $this->merge([
                'id' => (int) $this->route('id')
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $userToDelete = User::find($this->route('id'));
            
            if ($userToDelete) {
                // Tidak boleh menghapus diri sendiri
                if (auth()->id() === $userToDelete->id) {
                    $validator->errors()->add('id', 'Anda tidak dapat menghapus akun Anda sendiri');
                }
                
                // Tidak boleh menghapus super admin (jika ada role hierarchy)
                if ($userToDelete->role === 'admin' && auth()->user()->role !== 'super_admin') {
                    $validator->errors()->add('id', 'Anda tidak memiliki izin untuk menghapus admin lain');
                }
                
                // Cek apakah user memiliki data terkait (enrollment, dll)
                if ($userToDelete->enrollments()->exists()) {
                    $validator->errors()->add('id', 'Tidak dapat menghapus user yang memiliki pendaftaran aktif');
                }
            }
        });
    }

    /**
     * Get the validated user ID.
     */
    public function getUserId(): int
    {
        return (int) $this->route('id');
    }

    /**
     * Get the user to be deleted.
     */
    public function getUserToDelete(): ?User
    {
        return User::find($this->route('id'));
    }
}