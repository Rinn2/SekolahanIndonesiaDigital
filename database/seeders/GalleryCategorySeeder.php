<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryCategory;

class GalleryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pelatihan Digital Marketing',
                'description' => 'Kegiatan pelatihan digital marketing yang telah diselenggarakan',
            ],
            [
                'name' => 'Pelatihan Web Development',
                'description' => 'Kegiatan pelatihan web development dan programming',
            ],
            [
                'name' => 'Pelatihan Data Analysis',
                'description' => 'Kegiatan pelatihan analisis data dan statistik',
            ],
            [
                'name' => 'Pelatihan Graphic Design',
                'description' => 'Kegiatan pelatihan desain grafis dan multimedia',
            ],
            [
                'name' => 'Kegiatan Praktikum',
                'description' => 'Kegiatan praktikum dan hands-on training',
            ],
        ];

        foreach ($categories as $category) {
            GalleryCategory::create($category);
        }
    }
}
