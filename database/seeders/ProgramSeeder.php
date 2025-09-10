<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\User;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $programs = [
            [
                'name' => 'Pelatihan Komputer Dasar',
                'description' => 'Program pelatihan komputer dasar untuk pemula, meliputi penggunaan Microsoft Office, internet, dan email.',
                'duration_months' => 3,
                'max_participants' => 20,
                'price' => 500000.00,
                'status' => 'aktif',
                'is_active' => true,
                'level' => 'Pemula',
            ],
            [
                'name' => 'Pelatihan Web Design',
                'description' => 'Pelatihan desain web menggunakan HTML, CSS, dan JavaScript untuk membuat website yang menarik.',
                'duration_months' => 4,
                'max_participants' => 12,
                'price' => 1200000.00,
                'status' => 'aktif',
                'is_active' => true,
                'level' => 'Menengah',
            ],
            [
                'name' => 'Pelatihan Digital Marketing',
                'description' => 'Pelatihan digital marketing meliputi social media marketing, SEO, dan Google Ads.',
                'duration_months' => 3,
                'max_participants' => 25,
                'price' => 900000.00,
                'status' => 'tidak_aktif',
                'is_active' => false,
                'level' => 'Lanjutan',
            ], 
            [
                'name' => 'Pelatihan Jaringan Komputer',
                'description' => 'Pelatihan jaringan komputer mikrotik.',
                'duration_months' => 3,
                'max_participants' => 25,
                'price' => 0,
                'status' => 'aktif',
                'is_active' => true,
                'level' => 'Lanjutan',
            ],
        ];

        foreach ($programs as $program) {
            Program::create(array_merge($program, [
                'created_by' => $admin?->id,
            ]));
        }
    }
}
