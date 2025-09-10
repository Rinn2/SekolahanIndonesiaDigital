<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tabel utama untuk sertifikat
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->date('issue_date');
            $table->enum('status', ['Kompeten', 'Belum Kompeten'])->default('Belum Kompeten');
            $table->timestamps();
        });

        // Tabel master untuk unit kompetensi (DENGAN kategori)
        Schema::create('competency_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // Kolom baru untuk kategori
            $table->timestamps();
        });

        // Tabel pivot untuk relasi many-to-many antara certificates dan competency_units
        Schema::create('certificate_competency_unit', function (Blueprint $table) {
            $table->foreignId('certificate_id')->constrained()->onDelete('cascade');
            $table->foreignId('competency_unit_id')->constrained()->onDelete('cascade');
            $table->primary(['certificate_id', 'competency_unit_id']);
        });

        // Tabel untuk relasi program dengan unit kompetensi (opsional)
        Schema::create('program_competency_unit', function (Blueprint $table) {
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('competency_unit_id')->constrained()->onDelete('cascade');
            $table->primary(['program_id', 'competency_unit_id']);
        });

        // Seed data unit kompetensi
        $this->seedCompetencyUnits();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_competency_unit');
        Schema::dropIfExists('certificate_competency_unit');
        Schema::dropIfExists('competency_units');
        Schema::dropIfExists('certificates');
    }

    /**
     * Seed the initial competency units data.
     */
    private function seedCompetencyUnits()
    {
        DB::table('competency_units')->insert([
            // Kategori: Soft Skills / General
            [
                'unit_code' => 'J.61IFO00.001.2', 
                'title' => 'Melaksanakan Pekerjaan Secara Individu', 
                'description' => 'Unit kompetensi yang berkaitan dengan kemampuan melaksanakan pekerjaan secara individu',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.002.2', 
                'title' => 'Menerapkan Prosedur Keselamatan dan Kesehatan Kerja (K3)', 
                'description' => 'Unit kompetensi yang berkaitan dengan penerapan K3 di tempat kerja',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.003.2', 
                'title' => 'Menggunakan Alat Ukur dan Alat Bantu', 
                'description' => 'Unit kompetensi penggunaan alat ukur dan alat bantu kerja',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.004.2', 
                'title' => 'Membuat Laporan Tertulis', 
                'description' => 'Unit kompetensi pembuatan laporan kerja tertulis',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.005.2', 
                'title' => 'Melakukan Komunikasi di Tempat Kerja', 
                'description' => 'Unit kompetensi komunikasi efektif di lingkungan kerja',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.006.2', 
                'title' => 'Melaksanakan Pekerjaan Secara Tim', 
                'description' => 'Unit kompetensi kerja sama tim dalam pelaksanaan pekerjaan',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],

            // Kategori: Fiber Optik - Perencanaan dan Instalasi
            [
                'unit_code' => 'J.61IFO00.007.2', 
                'title' => 'Merencanakan Instalasi Fiber Optik Berdasarkan Peta As Planned Drawing', 
                'description' => 'Unit kompetensi perencanaan instalasi fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.008.2', 
                'title' => 'Memasang Kabel Fiber Optik Ruangan/Gedung', 
                'description' => 'Unit kompetensi pemasangan kabel fiber optik indoor',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.009.1', 
                'title' => 'Memasang Kabel Fiber Optik Udara', 
                'description' => 'Unit kompetensi pemasangan kabel fiber optik aerial',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.010.1', 
                'title' => 'Memasang Kabel Fiber Optik Tanam Langsung', 
                'description' => 'Unit kompetensi pemasangan kabel fiber optik direct buried',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.011.1', 
                'title' => 'Memasang Kabel Fiber Optik Duct', 
                'description' => 'Unit kompetensi pemasangan kabel fiber optik melalui duct',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],

            // Kategori: Fiber Optik - Testing dan Measurement
            [
                'unit_code' => 'J.61IFO00.012.2', 
                'title' => 'Mengoperasikan Power Meter', 
                'description' => 'Unit kompetensi pengoperasian power meter untuk pengujian fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.016.2', 
                'title' => 'Mengoperasikan OTDR (Optical Time Domain Reflectometer)', 
                'description' => 'Unit kompetensi pengoperasian OTDR untuk pengujian fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.017.2', 
                'title' => 'Melaksanakan Evaluasi Instalasi Fiber Optik Menggunakan OTDR', 
                'description' => 'Unit kompetensi evaluasi dan analisis hasil pengujian OTDR',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],

            // Kategori: Fiber Optik - Splicing dan Connecting
            [
                'unit_code' => 'J.61IFO00.013.2', 
                'title' => 'Memasang Konektor Fiber Optik', 
                'description' => 'Unit kompetensi pemasangan berbagai jenis konektor fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.014.2', 
                'title' => 'Melaksanakan Penyambungan Fiber Optik dengan Fusion Splicer', 
                'description' => 'Unit kompetensi penyambungan fiber optik menggunakan fusion splicer',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.015.2', 
                'title' => 'Melaksanakan Penyambungan Fiber Optik dengan Mechanical Splice', 
                'description' => 'Unit kompetensi penyambungan fiber optik menggunakan mechanical splice',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],

            // Kategori: Fiber Optik - Maintenance dan Troubleshooting
            [
                'unit_code' => 'J.61IFO00.018.2', 
                'title' => 'Melakukan Troubleshooting atas Masalah pada Instalasi Fiber Optik', 
                'description' => 'Unit kompetensi troubleshooting masalah instalasi fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'unit_code' => 'J.61IFO00.019.2', 
                'title' => 'Melaksanakan Komisioning dan Uji Terima (Acceptance Test) Instalasi Fiber Optik', 
                'description' => 'Unit kompetensi komisioning dan acceptance test instalasi fiber optik',
                'category' => 'Fiber Optik',
                'created_at' => now(), 
                'updated_at' => now()
            ]
        ]);
    }
};