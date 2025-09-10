<?php
// app/Repositories/Eloquent/GradeRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Grade;
use App\Repositories\Interfaces\GradeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GradeRepository implements GradeRepositoryInterface
{
    public function findByEnrollmentAndMeeting(int $enrollmentId, int $meetingNumber): ?Grade
    {
        return Grade::where('enrollment_id', $enrollmentId)
            ->where('meeting_number', $meetingNumber)
            ->first();
    }

    public function updateOrCreateGrade(array $conditions, array $data): Grade
    {
        return Grade::updateOrCreate($conditions, $data);
    }

    public function getGradesByEnrollment(int $enrollmentId): Collection
    {
        return Grade::where('enrollment_id', $enrollmentId)
            ->orderBy('meeting_number', 'asc')
            ->get();
    }

    public function deleteGrade(Grade $grade): bool
    {
        return $grade->delete();
    }

    public function getGradesBySchedule(int $scheduleId): Collection
    {
        return Grade::whereHas('enrollment.schedule', function ($query) use ($scheduleId) {
            $query->where('id', $scheduleId);
        })
        ->with(['enrollment.user'])
        ->get();
    }
}
