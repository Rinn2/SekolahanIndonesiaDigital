<?php
// app/Repositories/Interfaces/ScheduleRepositoryInterface.php

namespace App\Repositories\Interfaces;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface
{
    public function getByInstructor(int $instructorId): Collection;
    public function findByInstructorAndId(int $instructorId, int $scheduleId): ?Schedule;
    public function updateSchedule(Schedule $schedule, array $data): bool;
    public function getActiveSchedules(int $instructorId): Collection;
    public function getCompletedSchedules(int $instructorId): Collection;
    public function getUpcomingSchedules(int $instructorId, int $limit = 3): Collection;
    public function getScheduleWithEnrollments(int $scheduleId, int $instructorId): ?Schedule;
}