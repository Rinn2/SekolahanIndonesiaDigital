<?php

namespace App\Repositories\Interfaces;

use App\Models\Grade;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface GradeRepositoryInterface
{
    public function findByEnrollmentAndMeeting(int $enrollmentId, int $meetingNumber): ?Grade;
    public function updateOrCreateGrade(array $conditions, array $data): Grade;
    public function getGradesByEnrollment(int $enrollmentId): Collection;
    public function deleteGrade(Grade $grade): bool;
    public function getGradesBySchedule(int $scheduleId): Collection;
}