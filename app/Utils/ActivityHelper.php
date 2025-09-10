<?php
// app/Utils/ActivityHelper.php

namespace App\Utils;

use App\Models\Enrollment;
use App\Models\Schedule;

class ActivityHelper
{
    protected $statusHelper;

    public function __construct(StatusHelper $statusHelper)
    {
        $this->statusHelper = $statusHelper;
    }

    public function createEnrollmentActivity(Enrollment $enrollment): array
    {
        $statusText = $this->statusHelper->getStatusText($enrollment->status);
        
        return [
            'icon' => 'user-plus',
            'color' => $this->statusHelper->getStatusColor($enrollment->status),
            'message' => $enrollment->user->name . ' mendaftar ke ' . $enrollment->program->name . ' - Status: ' . $statusText,
            'time' => $enrollment->created_at->diffForHumans()
        ];
    }

    public function createScheduleActivity(Schedule $schedule): array
    {
        return [
            'icon' => 'calendar',
            'color' => 'green',
            'message' => 'Jadwal ' . $schedule->title . ' akan dimulai',
            'time' => $schedule->start_date->diffForHumans()
        ];
    }

    public function createGradeActivity(string $studentName, string $programName, float $grade): array
    {
        return [
            'icon' => 'book',
            'color' => $grade >= 70 ? 'green' : ($grade >= 60 ? 'yellow' : 'red'),
            'message' => "Nilai {$grade} diberikan kepada {$studentName} untuk {$programName}",
            'time' => now()->diffForHumans()
        ];
    }
}