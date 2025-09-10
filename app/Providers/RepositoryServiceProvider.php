<?php
// app/Providers/RepositoryServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repository Interfaces
use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\GradeRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;

// Repository Implementations
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\EnrollmentRepository;
use App\Repositories\Eloquent\GradeRepository;
use App\Repositories\Eloquent\ProgramRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to their Eloquent implementations
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(GradeRepositoryInterface::class, GradeRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);

        // Register utility classes as singletons
        $this->app->singleton(\App\Utils\StatusHelper::class);
        $this->app->singleton(\App\Utils\ActivityLogger::class);
        $this->app->singleton(\App\Utils\CsvExporter::class);
        $this->app->singleton(\App\Utils\ActivityHelper::class);
        $this->app->singleton(\App\Utils\DateHelper::class);

        // Register services
        $this->app->bind(\App\Services\Instruktur\DashboardService::class);
        $this->app->bind(\App\Services\Instruktur\ScheduleService::class);
        $this->app->bind(\App\Services\Instruktur\EnrollmentService::class);
        $this->app->bind(\App\Services\Instruktur\GradeService::class);
        $this->app->bind(\App\Services\Instruktur\ExportService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            ScheduleRepositoryInterface::class,
            EnrollmentRepositoryInterface::class,
            GradeRepositoryInterface::class,
            ProgramRepositoryInterface::class,
            \App\Utils\StatusHelper::class,
            \App\Utils\ActivityLogger::class,
            \App\Utils\CsvExporter::class,
            \App\Utils\ActivityHelper::class,
            \App\Utils\DateHelper::class,
            \App\Services\Instruktur\DashboardService::class,
            \App\Services\Instruktur\ScheduleService::class,
            \App\Services\Instruktur\EnrollmentService::class,
            \App\Services\Instruktur\GradeService::class,
            \App\Services\Instruktur\ExportService::class,
        ];
    }
}