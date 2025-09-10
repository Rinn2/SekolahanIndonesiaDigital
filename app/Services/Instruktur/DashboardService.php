<?php
// app/Services/Instruktur/DashboardService.php

namespace App\Services\Instruktur;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Utils\StatusHelper;
use App\Utils\ActivityHelper;
use Illuminate\Support\Collection;

class DashboardService
{
    protected $scheduleRepository;
    protected $enrollmentRepository;
    protected $programRepository;
    protected $statusHelper;
    protected $activityHelper;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        EnrollmentRepositoryInterface $enrollmentRepository,
        ProgramRepositoryInterface $programRepository,
        StatusHelper $statusHelper,
        ActivityHelper $activityHelper
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->programRepository = $programRepository;
        $this->statusHelper = $statusHelper;
        $this->activityHelper = $activityHelper;
    }

    public function getDashboardData(int $instructorId): array
    {
        $mySchedules = $this->scheduleRepository->getByInstructor($instructorId);
        $statistics = $this->getStatistics($instructorId, $mySchedules);
        $recentActivities = $this->getRecentActivities($instructorId);
        $enrollments = $this->enrollmentRepository->getByInstructor($instructorId);
        $programs = $this->programRepository->getProgramsWithEnrollmentCount($instructorId);

        return [
            'mySchedules' => $mySchedules,
            'totalMySchedules' => $statistics['totalMySchedules'],
            'activeSchedules' => $statistics['activeSchedules'],
            'totalStudents' => $statistics['totalStudents'],
            'completedSchedules' => $statistics['completedSchedules'],
            'recentActivities' => $recentActivities,
            'enrollments' => $enrollments,
            'programs' => $programs
        ];
    }

    protected function getStatistics(int $instructorId, Collection $mySchedules): array
    {
        $activeSchedules = $this->scheduleRepository->getActiveSchedules($instructorId);
        $completedSchedules = $this->scheduleRepository->getCompletedSchedules($instructorId);
        $totalStudents = $this->enrollmentRepository->getAcceptedStudentsCount($instructorId);

        return [
            'totalMySchedules' => $mySchedules->count(),
            'activeSchedules' => $activeSchedules->count(),
            'totalStudents' => $totalStudents,
            'completedSchedules' => $completedSchedules->count()
        ];
    }

    protected function getRecentActivities(int $instructorId): Collection
    {
        $activities = collect();

        // Recent enrollments
        $recentEnrollments = $this->enrollmentRepository->getRecentEnrollments($instructorId);
        foreach ($recentEnrollments as $enrollment) {
            $activities->push($this->activityHelper->createEnrollmentActivity($enrollment));
        }

        // Upcoming schedules
        $upcomingSchedules = $this->scheduleRepository->getUpcomingSchedules($instructorId, 3);
        foreach ($upcomingSchedules as $schedule) {
            $activities->push($this->activityHelper->createScheduleActivity($schedule));
        }

        return $activities->sortByDesc('time')->take(10);
    }
}




