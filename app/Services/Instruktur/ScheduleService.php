<?php

namespace App\Services\Instruktur;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Exceptions\Instruktur\ScheduleNotFoundException;
use App\Utils\ActivityLogger;
use App\Models\Schedule;

class ScheduleService
{
    protected $scheduleRepository;
    protected $activityLogger;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        ActivityLogger $activityLogger
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->activityLogger = $activityLogger;
    }

    public function getSchedule(int $scheduleId, int $instructorId): Schedule
    {
        $schedule = $this->scheduleRepository->findByInstructorAndId($instructorId, $scheduleId);
        
        if (!$schedule) {
            throw new ScheduleNotFoundException('Jadwal tidak ditemukan');
        }

        return $schedule;
    }

    public function updateSchedule(int $scheduleId, int $instructorId, array $data): bool
    {
        $schedule = $this->getSchedule($scheduleId, $instructorId);
        
        $updated = $this->scheduleRepository->updateSchedule($schedule, $data);
        
        if ($updated) {
            $this->activityLogger->log('update_schedule', "Memperbarui jadwal: {$schedule->title}");
        }

        return $updated;
    }

    public function getStudentProgress(int $scheduleId, int $instructorId): array
    {
        $schedule = $this->scheduleRepository->getScheduleWithEnrollments($scheduleId, $instructorId);
        
        if (!$schedule) {
            throw new ScheduleNotFoundException('Jadwal tidak ditemukan');
        }

        return $schedule->enrollments->map(function ($enrollment) {
            return [
                'id' => $enrollment->user->id,
                'name' => $enrollment->user->name,
                'email' => $enrollment->user->email,
                'enrollment_date' => $enrollment->created_at->format('d M Y'),
                'status' => $enrollment->status,
                'status_text' => app(StatusHelper::class)->getStatusText($enrollment->status),
                'payment_status' => $enrollment->payment_status ?? 'pending',
                'progress' => rand(0, 100) // This would be real progress data
            ];
        })->toArray();
    }
}
