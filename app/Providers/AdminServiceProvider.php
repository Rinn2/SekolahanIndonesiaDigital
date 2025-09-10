<?php

// app/Providers/AdminServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Admin\DashboardServiceInterface;
use App\Contracts\Admin\UserServiceInterface;
use App\Contracts\Admin\ProgramServiceInterface;
use App\Contracts\Admin\ScheduleServiceInterface;
use App\Contracts\Admin\EnrollmentServiceInterface;
use App\Contracts\Admin\InstructorServiceInterface;
use App\Services\Admin\DashboardService;
use App\Services\Admin\UserService;
use App\Services\Admin\ProgramService;
use App\Services\Admin\ScheduleService;
use App\Services\Admin\EnrollmentService;
use App\Services\Admin\InstructorService;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(ProgramServiceInterface::class, ProgramService::class);
        $this->app->bind(ScheduleServiceInterface::class, ScheduleService::class);
        $this->app->bind(EnrollmentServiceInterface::class, EnrollmentService::class);
        $this->app->bind(InstructorServiceInterface::class, InstructorService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}