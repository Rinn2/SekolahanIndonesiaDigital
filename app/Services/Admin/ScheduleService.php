<?php

namespace App\Services\Admin;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Program;
use App\Contracts\Admin\ScheduleServiceInterface;
use App\Exceptions\ScheduleCannotBeDeletedException;
use Carbon\Carbon;

class ScheduleService implements ScheduleServiceInterface
{
    public function findByIdWithRelations(int $id): array
    {
        $schedule = Schedule::with(['program', 'instructor'])->findOrFail($id);
        
        return [
            'id' => $schedule->id,
            'title' => $schedule->title,
            'program_id' => $schedule->program_id,
            'instructor_id' => $schedule->instructor_id,
            'start_date' => $schedule->start_date ? Carbon::parse($schedule->start_date)->format('Y-m-d') : '',
            'end_date' => $schedule->end_date ? Carbon::parse($schedule->end_date)->format('Y-m-d') : '',
            'location' => $schedule->location,
            'max_participants' => $schedule->max_participants,
            'program' => $schedule->program,
            'instructor' => $schedule->instructor
        ];
    }

    public function create(array $data): Schedule
    {
        $scheduleData = [
            'title' => $data['title'],
            'program_id' => $data['program_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'location' => $data['location'] ?? null,
            'max_participants' => $data['max_participants'] ?? null,
        ];

        if (!empty($data['instructor_id'])) {
            $scheduleData['instructor_id'] = $data['instructor_id'];
        }

        $schedule = Schedule::create($scheduleData);
        
        return $schedule->load(['program', 'instructor']);
    }

    public function update(int $id, array $data): Schedule
    {
        $schedule = Schedule::findOrFail($id);

        $updateData = [
            'title' => $data['title'],
            'program_id' => $data['program_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'location' => $data['location'] ?? null,
            'max_participants' => $data['max_participants'] ?? null,
        ];

        $updateData['instructor_id'] = !empty($data['instructor_id']) 
            ? $data['instructor_id'] 
            : null;

        $schedule->update($updateData);
        
        return $schedule->load(['program', 'instructor']);
    }

    public function delete(int $id): void
    {
        $schedule = Schedule::findOrFail($id);
        
        if ($schedule->enrollments()->exists()) {
            throw new ScheduleCannotBeDeletedException(
                'Tidak dapat menghapus jadwal yang memiliki pendaftaran',
                422
            );
        }

        $schedule->delete();
    }

    public function getFormData(): array
    {
        return [
            'programs' => Program::where('is_active', true)->get(['id', 'name']),
            'instructors' => User::where('role', 'instruktur')->get(['id', 'name'])
        ];
    }
}