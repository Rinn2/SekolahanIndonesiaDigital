<?php
// app/Repositories/Eloquent/EnrollmentRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function getByInstructor(int $instructorId): Collection
    {
        return Enrollment::whereHas('schedule', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->with(['user', 'program', 'schedule'])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function findByInstructorAndId(int $instructorId, int $enrollmentId): ?Enrollment
    {
        return Enrollment::whereHas('schedule', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->where('id', $enrollmentId)
        ->with(['user', 'program', 'schedule'])
        ->first();
    }

    public function updateEnrollment(Enrollment $enrollment, array $data): bool
    {
        return $enrollment->update($data);
    }

    public function getRecentEnrollments(int $instructorId, int $limit = 5): Collection
    {
        return Enrollment::whereHas('schedule', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->with(['user', 'program', 'schedule'])
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
    }

    public function getAcceptedStudentsCount(int $instructorId): int
    {
        return Enrollment::whereHas('schedule', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->where('status', 'diterima')
        ->count();
    }

    public function getEnrollmentsBySchedule(int $scheduleId, string $status = null): Collection
    {
        $query = Enrollment::where('schedule_id', $scheduleId)
            ->with(['user', 'program', 'schedule']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function getEnrollmentWithRelations(int $enrollmentId, int $instructorId): ?Enrollment
    {
        return Enrollment::with(['user', 'program', 'schedule'])
            ->where('id', $enrollmentId)
            ->whereHas('schedule', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->first();
    }
}
