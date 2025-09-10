<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Contracts\Admin\InstructorServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class InstructorService implements InstructorServiceInterface
{
    public function getAllInstructors(): Collection
    {
        return User::where('role', 'instruktur')->get(['id', 'name']);
    }
}