<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan data lama sesuai dengan enum baru
        // Ubah 'tidak_lulus' menjadi 'dropout' agar tidak error saat alter table
        DB::table('enrollments')
            ->where('status', 'tidak_lulus')
            ->update(['status' => 'dropout']);

        // Alter column dengan enum baru
        DB::statement("
            ALTER TABLE enrollments 
            MODIFY COLUMN status ENUM('pending', 'diterima', 'ditolak', 'lulus', 'dropout') 
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan data 'dropout' jadi 'tidak_lulus' agar konsisten
        DB::table('enrollments')
            ->where('status', 'dropout')
            ->update(['status' => 'tidak_lulus']);

        // Alter column kembali ke enum lama
        DB::statement("
            ALTER TABLE enrollments 
            MODIFY COLUMN status ENUM('pending', 'diterima', 'ditolak', 'lulus', 'tidak_lulus') 
            NOT NULL DEFAULT 'pending'
        ");
    }
};
