<?php

namespace App\Services\Admin;

use App\Models\Enrollment;
use App\Contracts\Admin\EnrollmentServiceInterface;

class EnrollmentService implements EnrollmentServiceInterface
{
    public function updateStatus(int $id, array $data): Enrollment
    {
        $enrollment = Enrollment::findOrFail($id);

        $updateData = [
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'completion_date' => in_array($data['status'], ['lulus', 'dropout']) ? now() : null
        ];

        $enrollment->update($updateData);
        
        return $enrollment->load(['user', 'program', 'schedule']);
    }

    public function delete(int $id): void
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->delete();
    }
}