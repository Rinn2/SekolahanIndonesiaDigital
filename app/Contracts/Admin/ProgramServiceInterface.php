<?php
namespace App\Contracts\Admin;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;

interface ProgramServiceInterface
{
    public function getActivePrograms(): Collection;
    public function findById(int $id): Program;
    public function create(array $data): Program;
    public function update(int $id, array $data): Program;
    public function delete(int $id): void;
}