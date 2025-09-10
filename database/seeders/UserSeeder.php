<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sekolah.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => 'Jl. Pendidikan No. 1, Bandung',
            'gender' => 'L',
            'birth_date' => '1980-01-15',
            'education' => 'S1',
            'role' => 'admin',
        ]);

        // Instruktur
        $instructors = [
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi@sekolah.id',
                'phone' => '081234567891',
                'address' => 'Jl. Guru No. 12, Bandung',
                'gender' => 'L',
                'birth_date' => '1975-03-20',
                'education' => 'S2',
            ],
            [
                'name' => 'Siti Nurhaliza, M.Pd',
                'email' => 'siti@sekolah.id',
                'phone' => '081234567892',
                'address' => 'Jl. Pendidik No. 8, Bandung',
                'gender' => 'P',
                'birth_date' => '1982-07-10',
                'education' => 'S2',
            ],
            [
                'name' => 'Ahmad Fauzi, S.Kom',
                'email' => 'ahmad@sekolah.id',
                'phone' => '081234567893',
                'address' => 'Jl. Teknologi No. 5, Bandung',
                'gender' => 'L',
                'birth_date' => '1985-11-25',
                'education' => 'S1',
            ],
        ];

        foreach ($instructors as $instructor) {
            User::create(array_merge($instructor, [
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'instruktur',
            ]));
        }

        // Peserta
        $participants = [
            [
                'name' => 'Andi Wijaya',
                'email' => 'andi@gmail.com',
                'phone' => '081234567894',
                'address' => 'Jl. Merdeka No. 10, Bandung',
                'gender' => 'L',
                'birth_date' => '2000-05-15',
                'education' => 'SMA/SMK',
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi@gmail.com',
                'phone' => '081234567895',
                'address' => 'Jl. Kartini No. 7, Bandung',
                'gender' => 'P',
                'birth_date' => '1999-08-22',
                'education' => 'SMA/SMK',
            ],
            [
                'name' => 'Reza Pratama',
                'email' => 'reza@gmail.com',
                'phone' => '081234567896',
                'address' => 'Jl. Diponegoro No. 15, Bandung',
                'gender' => 'L',
                'birth_date' => '2001-02-10',
                'education' => 'SMA/SMK',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya@gmail.com',
                'phone' => '081234567897',
                'address' => 'Jl. Sudirman No. 20, Bandung',
                'gender' => 'P',
                'birth_date' => '2000-12-05',
                'education' => 'SMA/SMK',
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas@gmail.com',
                'phone' => '081234567898',
                'address' => 'Jl. Thamrin No. 3, Bandung',
                'gender' => 'L',
                'birth_date' => '1998-09-18',
                'education' => 'D3',
            ],
                [
                'name' => 'Kemal',
                'email' => 'm.kemal.ardhika@gmail.com',
                'phone' => '081234567897',
                'address' => 'Jl. Sudirman No. 20, Bandung',
                'gender' => 'L',
                'birth_date' => '2004-08-24',
                'education' => 'S1',
            ],
              [
                'name' => 'Furora Mare',
                'email' => 'furoramare@gmail.com',
                'phone' => '081234567897',
                'address' => 'Jl. Sudirman No. 20, Bandung',
                'gender' => 'P',
                'birth_date' => '2004-08-24',
                'education' => 'S1',
            ],
        ];

        foreach ($participants as $participant) {
            User::create(array_merge($participant, [
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]));
        }
    }
}
