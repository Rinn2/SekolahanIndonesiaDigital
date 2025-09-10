<?php
// app/Repositories/Eloquent/ScheduleRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getByInstructor(int $instructorId): Collection
    {
        return Schedule::where('instructor_id', $instructorId)
            ->with(['program', 'enrollments.user'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function findByInstructorAndId(int $instructorId, int $scheduleId): ?Schedule
    {
        return Schedule::where('id', $scheduleId)
            ->where('instructor_id', $instructorId)
            ->with(['program', 'enrollments.user'])
            ->first();
    }

    public function updateSchedule(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    public function getActiveSchedules(int $instructorId): Collection
    {
        return Schedule::where('instructor_id', $instructorId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();
    }

    public function getCompletedSchedules(int $instructorId): Collection
    {
        return Schedule::where('instructor_id', $instructorId)
            ->where('end_date', '<', now())
            ->get();
    }

    public function getUpcomingSchedules(int $instructorId, int $limit = 3): Collection
    {
        return Schedule::where('instructor_id', $instructorId)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getScheduleWithEnrollments(int $scheduleId, int $instructorId): ?Schedule
    {
        return Schedule::where('id', $scheduleId)
            ->where('instructor_id', $instructorId)
            ->with(['enrollments' => function ($query) {
                $query->where('status', 'diterima')->with('user');
            }])
            ->first();
    }
}


