<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Program;
use App\Models\Schedule;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $participants = User::where('role', 'peserta')->get();
        $programs = Program::where('is_active', true)->take(3)->get();

        foreach ($participants as $index => $participant) {
            $program = $programs[$index % $programs->count()];
            $schedule = Schedule::where('program_id', $program->id)->first();

            $statuses = ['pending', 'diterima', 'lulus'];
            $status = $statuses[$index % count($statuses)];

            $enrollment = [
                'user_id' => $participant->id,
                'program_id' => $program->id,
                'schedule_id' => $schedule->id,
                'status' => $status,
                'enrollment_date' => Carbon::now()->subDays(rand(1, 30)),
                'notes' => 'Pendaftaran melalui sistem online',
            ];

            // Jika status lulus, tambahkan completion_date
            if ($status === 'lulus') {
                $enrollment['completion_date'] = Carbon::now()->subDays(rand(1, 10));
            }

            Enrollment::create($enrollment);
        }

        // Tambahan enrollment untuk testing
        $extraEnrollments = [
            [
                'user_id' => $participants[0]->id,
                'program_id' => $programs[1]->id,
                'schedule_id' => Schedule::where('program_id', $programs[1]->id)->first()->id,
                'status' => 'ditolak',
                'enrollment_date' => Carbon::now()->subDays(15),
                'notes' => 'Tidak memenuhi persyaratan minimum',
            ],
            [
                'user_id' => $participants[1]->id,
                'program_id' => $programs[0]->id,
                'schedule_id' => Schedule::where('program_id', $programs[0]->id)->first()->id,
                'status' => 'dropout',
                'enrollment_date' => Carbon::now()->subDays(45),
                'notes' => 'Mengundurkan diri karena kesibukan kerja',
            ],
        ];

        foreach ($extraEnrollments as $enrollment) {
            Enrollment::create($enrollment);
        }
    }
}