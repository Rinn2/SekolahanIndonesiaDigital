<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CompetencyUnitsSeeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProgramSeeder::class,
            ScheduleSeeder::class,
            EnrollmentSeeder::class,
            GallerySeeder::class,
            CompetencyUnitsSeeder::class,
            GalleryCategorySeeder::class,
        ]);
    }
}