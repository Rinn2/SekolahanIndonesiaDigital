<?php
namespace App\Contracts\Admin;

use Illuminate\Database\Eloquent\Collection;

interface InstructorServiceInterface
{
    public function getAllInstructors(): Collection;
}