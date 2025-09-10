<?php
namespace App\Contracts\Admin;

use App\Models\Enrollment;

interface EnrollmentServiceInterface
{
    public function updateStatus(int $id, array $data): Enrollment;
    public function delete(int $id): void;
}