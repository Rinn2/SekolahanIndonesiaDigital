<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Contracts\Admin\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    public function getDashboardData(): array
    {
        return [
            'totalUsers' => $this->getTotalUsers(),
            'totalPrograms' => $this->getTotalPrograms(),
            'activeEnrollments' => $this->getActiveEnrollments(),
            'totalSchedules' => $this->getTotalSchedules(),
            'recentActivities' => $this->getRecentActivities(),
            'users' => $this->getRecentUsers(),
            'programs' => $this->getRecentPrograms(),
            'schedules' => $this->getRecentSchedules(),
            'enrollments' => $this->getRecentEnrollments(),
            'instructors' => $this->getInstructors()
        ];
    }

    private function getTotalUsers(): int
    {
        return User::count();
    }

    private function getTotalPrograms(): int
    {
        return Program::count();
    }

    private function getActiveEnrollments(): int
    {
        return Enrollment::where('status', 'diterima')->count();
    }

    private function getTotalSchedules(): int
    {
        return Schedule::count();
    }

    private function getRecentUsers()
    {
        return User::with('enrollments')->latest()->take(10)->get();
    }

    private function getRecentPrograms()
    {
        return Program::with('enrollments')->latest()->take(10)->get();
    }

    private function getRecentSchedules()
    {
        return Schedule::with(['program', 'instructor'])->latest()->take(10)->get();
    }

    private function getRecentEnrollments()
    {
        return Enrollment::with(['user', 'program', 'schedule'])->latest()->take(10)->get();
    }

    private function getInstructors()
    {
        return User::where('role', 'instruktur')->get();
    }

    private function getRecentActivities(): array
{
    $activities = collect();

    // Recent user registrations
    $recentUsers = User::latest()->take(3)->get();
    foreach ($recentUsers as $user) {
        $activities->push([
            'icon' => 'user-plus',
            'color' => 'blue',
            'message' => "Pengguna baru {$user->name} telah mendaftar",
            'time' => $user->created_at
        ]);
    }

    // Recent enrollments
    $recentEnrollments = Enrollment::with(['user', 'program'])
        ->latest()
        ->take(3)
        ->get();
    foreach ($recentEnrollments as $enrollment) {
    $user = $enrollment->user;
    $program = $enrollment->program;

    if ($user && $program) {
        $activities->push([
            'icon' => 'book-open',
            'color' => 'green',
            'message' => "{$user->name} mendaftar ke {$program->name}",
            'time' => $enrollment->created_at
        ]);
    }
}

    // Recent schedules
    $recentSchedules = Schedule::with('program')
        ->latest()
        ->take(2)
        ->get();
    foreach ($recentSchedules as $schedule) {
        $activities->push([
            'icon' => 'calendar',
            'color' => 'purple',
            'message' => "Jadwal baru '{$schedule->title}' telah dibuat",
            'time' => $schedule->created_at
        ]);
    }

    // Sort by time descending
    $sorted = $activities->sortByDesc('time')->take(8);

    // Format diffForHumans
    return $sorted->map(function ($activity) {
        $activity['time'] = $activity['time']->diffForHumans();
        return $activity;
    })->values()->toArray();
}

}