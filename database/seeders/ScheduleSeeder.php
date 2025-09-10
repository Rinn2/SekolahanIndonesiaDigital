<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::where('is_active', true)->get();
        $instructors = User::where('role', 'instruktur')->get();

        foreach ($programs as $index => $program) {
            $instructor = $instructors[$index % $instructors->count()];
            
            // Jadwal Batch 1
            Schedule::create([
                'program_id' => $program->id,
                'instructor_id' => $instructor->id,
                'title' => $program->name . ' - Batch 1',
                'description' => 'Batch pertama untuk program ' . $program->name,
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(7)->addMonths($program->duration_months),
                'location' => 'Ruang Kelas ' . ($index + 1),
                'max_participants' => $program->max_participants,
            ]);

            // Jadwal Batch 2 (bulan depan)
            Schedule::create([
                'program_id' => $program->id,
                'instructor_id' => $instructor->id,
                'title' => $program->name . ' - Batch 2',
                'description' => 'Batch kedua untuk program ' . $program->name,
                'start_date' => Carbon::now()->addMonth()->addDays(7),
                'end_date' => Carbon::now()->addMonth()->addDays(7)->addMonths($program->duration_months),
                'location' => 'Ruang Kelas ' . ($index + 1),
                'max_participants' => $program->max_participants,
            ]);
        }
    }
}
