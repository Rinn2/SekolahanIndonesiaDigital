<?php

namespace App\Repositories\Interfaces;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;

interface ProgramRepositoryInterface
{
    public function getByInstructorId(int $instructorId): Collection;  // Changed this line
    public function findById(int $programId): ?Program;
    public function getProgramsWithEnrollmentCount(int $instructorId): Collection;
}