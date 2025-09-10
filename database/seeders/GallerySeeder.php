<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\GalleryCategory;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = GalleryCategory::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please run GalleryCategorySeeder first.');
            return;
        }

        $galleries = [
            [
                'title' => 'Pelatihan Digital Marketing Batch 1',
                'description' => 'Kegiatan pelatihan digital marketing yang diikuti oleh 25 peserta dengan materi SEO, SEM, dan Social Media Marketing.',
                'image' => 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nqCUzoeY7gUgvxNAf4CTavzpGy3-OkZoWYbSb5DoZL7OryuNbqxuXJNnwKnboFTNZk15EOtk6WWsP3LA6zhzhoYmZFFSzrUeikcYhoQc2e8y-tp_GrDxXIGv_KDmyqIeh9x_OCUFw=s1360-w1360-h1020-rw',
                'category_id' => $categories->where('name', 'Pelatihan Digital Marketing')->first()->id,
            ],
            [
                'title' => 'Workshop Web Development',
                'description' => 'Workshop intensif web development menggunakan teknologi modern seperti Laravel, Vue.js, dan Tailwind CSS.',
                'image' => 'https://lh3.googleusercontent.com/p/AF1QipN_VwpOwCoByhKduv-Oe3M6LhqpTzfgY7wDOFy8=s1360-w1360-h1020-rw',
                'category_id' => $categories->where('name', 'Pelatihan Web Development')->first()->id,
            ],
            [
                'title' => 'Pelatihan Data Analysis',
                'description' => 'Pelatihan analisis data menggunakan tools seperti Excel, Python, dan Tableau untuk pengambilan keputusan bisnis.',
                'image' => 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nqCOcqsFpLrBFy2gD8KRwbqrChvqDWaJgJMe9was8WvMTstb9j14vPI3l0yw0FWqz-fpRFwzO4Z2hk9LWHV6_Cs1KoNY-iCppnHBr5A6gyCUM7TFgWRW36pdvqH4HZtfN0r_uM=s1360-w1360-h1020-rw',
                'category_id' => $categories->where('name', 'Pelatihan Data Analysis')->first()->id,
            ],
            [
                'title' => 'Kelas Graphic Design',
                'description' => 'Kelas desain grafis yang mengajarkan penggunaan Adobe Photoshop, Illustrator, dan InDesign.',
                'image' => 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nr_ILv-QDZaiJbVTkNWD1SSHcCdc07cgPheIlpfZ2mxHZ5Hphfa_PVPYDer0Ct9btlvjKY-Yr3N_cBaV1jfbk5sTwULmYgJUL6ixrCImY15_T3CVXnssDPcafTJpojcMPmcH_I=s1360-w1360-h1020-rw',
                'category_id' => $categories->where('name', 'Pelatihan Graphic Design')->first()->id,
            ],
            [
                'title' => 'Praktikum Hands-on Training',
                'description' => 'Sesi praktikum langsung dengan instruktur berpengalaman untuk memastikan peserta memahami materi dengan baik.',
                'image' => 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4npqixXgd6xT9lWr5h0BPrSQ46ZthQp-4WBDiL6KpUOWc5FETSL681dRDRXkqWHkcbhhjbdv2e0NpMH0qb6461q0zRciEHKuqv_ritGudlUOgiCqW_q9TVgI9_MY_TNSKto3FElEmg=s1360-w1360-h1020-rw',
                'category_id' => $categories->where('name', 'Kegiatan Praktikum')->first()->id,
            ],
        ];

        // Clear existing galleries first
        Gallery::truncate();

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }

        $this->command->info('Gallery seeder completed successfully!');
    }
}
