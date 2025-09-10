<?php

namespace App\Repositories\Interfaces;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function getByInstructor(int $instructorId): Collection;
    public function findByInstructorAndId(int $instructorId, int $enrollmentId): ?Enrollment;
    public function updateEnrollment(Enrollment $enrollment, array $data): bool;
    public function getRecentEnrollments(int $instructorId, int $limit = 5): Collection;
    public function getAcceptedStudentsCount(int $instructorId): int;
    public function getEnrollmentsBySchedule(int $scheduleId, string $status = null): Collection;
    public function getEnrollmentWithRelations(int $enrollmentId, int $instructorId): ?Enrollment;
}