<?php

namespace App\Services\Admin;

use App\Models\Program;
use App\Contracts\Admin\ProgramServiceInterface;
use App\Exceptions\ProgramCannotBeDeletedException;
use Illuminate\Database\Eloquent\Collection;

class ProgramService implements ProgramServiceInterface
{
    public function getActivePrograms(): Collection
    {
        return Program::where('is_active', true)->get(['id', 'name']);
    }

    public function findById(int $id): Program
    {
        return Program::findOrFail($id);
    }

    public function create(array $data): Program
    {
        $programData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration_months' => $data['duration_months'],
            'level' => $data['level'],
            'max_participants' => $data['max_participants'],
            'price' => $data['price'] ?? null,
            'created_by' => auth()->id(),
            'status' => 'aktif',
            'is_active' => true
        ];

        return Program::create($programData);
    }

    public function update(int $id, array $data): Program
    {
        $program = $this->findById($id);

        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration_months' => $data['duration_months'],
            'level' => $data['level'],
            'max_participants' => $data['max_participants'],
            'price' => $data['price'] ?? null,
        ];

        $program->update($updateData);
        
        return $program->fresh();
    }

    public function delete(int $id): void
    {
        $program = $this->findById($id);
        
        if ($program->enrollments()->whereIn('status', ['pending', 'diterima'])->exists()) {
            throw new ProgramCannotBeDeletedException(
                'Tidak dapat menghapus program yang memiliki pendaftaran aktif',
                422
            );
        }

        $program->delete();
    }
}