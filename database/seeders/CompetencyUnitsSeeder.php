<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetencyUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('competency_units')->insert([
            [
                'unit_code'   => 'J.630PR00.001.2',
                'title'       => 'Menggunakan Perangkat Komputer',
                'description' => 'SKKNI Pengoperasian Komputer Nomor 56 Tahun 2018',
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'J.630PR00.007.2',
                'title'       => 'Menggunakan Penelusur Situs Web (web browser)',
                'description' => 'SKKNI Pengoperasian Komputer Nomor 56 Tahun 2018',
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.009.2',
                'title'       => 'Merencanakan Riset Terhadap Sebuah Produk dan/atau Merek',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.010.2',
                'title'       => 'Mengolah Data Riset',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.017.1',
                'title'       => 'Melaksanakan Kegiatan Promosi Merek',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'G.46RIT00.055.1',
                'title'       => 'Melakukan Aktivitas Pemasaran Digital untuk Bisnis Ritel',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.012.1',
                'title'       => 'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.013.1',
                'title'       => 'Melaksanakan Kegiatan Analisis di Media Sosial dan Media Bisnis Digital',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.014.1',
                'title'       => 'Mempersiapkan Konten Digital',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.015.1',
                'title'       => 'Mengoptimalkan Pengelolaan Media Sosial dan Rencana Aplikasi Digital',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'unit_code'   => 'M.70MKT00.033.2',
                'title'       => 'Mengembangkan Pengetahuan Produk',
                'description' => null,
                'category'    => 'Digital Marketing',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
