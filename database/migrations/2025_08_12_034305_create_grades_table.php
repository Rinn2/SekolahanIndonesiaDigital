<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{// dalam file ****_create_grades_table.php
public function up()
{
    Schema::create('grades', function (Blueprint $table) {
        $table->id();
        $table->foreignId('enrollment_id')->constrained()->onDelete('cascade'); // Relasi ke pendaftaran siswa
        $table->unsignedInteger('meeting_number'); // Untuk menandai pertemuan ke-berapa
        $table->decimal('grade', 5, 2)->nullable(); // Nilai, misal 95.50
        $table->text('notes')->nullable(); // Catatan dari instruktur
        $table->timestamps();

        // Membuat setiap siswa hanya punya satu nilai per pertemuan
        $table->unique(['enrollment_id', 'meeting_number']);
    });
}
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
