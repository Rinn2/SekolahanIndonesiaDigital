<?php

namespace App\Repositories\Eloquent;

use App\Models\Program;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProgramRepository implements ProgramRepositoryInterface
{
    public function getByInstructorId(int $instructorId): Collection
    {
        return Program::whereHas('schedules', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->get();
    }

    public function getProgramsByInstructor(int $instructorId): Collection
    {
        return Program::whereHas('schedules', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->get();
    }

    public function findById(int $programId): ?Program
    {
        return Program::find($programId);
    }

    public function getProgramsWithEnrollmentCount(int $instructorId): Collection
    {
        return Program::whereHas('schedules', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })
        ->withCount('enrollments')
        ->get();
    }
}