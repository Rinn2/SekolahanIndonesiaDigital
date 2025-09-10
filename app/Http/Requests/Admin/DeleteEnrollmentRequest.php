<?php

// app/Http/Requests/Admin/DeleteUserRequest.php
namespace App\Http\Requests\Admin;

use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteEnrollmentRequest extends FormRequest
{
    public function deleteEnrollment($id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}