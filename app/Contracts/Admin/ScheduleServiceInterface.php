<?php
namespace App\Contracts\Admin;

use App\Models\Schedule;

interface ScheduleServiceInterface
{
    public function findByIdWithRelations(int $id): array;
    public function create(array $data): Schedule;
    public function update(int $id, array $data): Schedule;
    public function delete(int $id): void;
    public function getFormData(): array;
}