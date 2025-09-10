<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nik')->unique()->nullable();
            $table->string('programstudi')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('pekerjaan', [
                'Belum/Tidak Bekerja',
                'Mengurus Rumah Tangga',
                'Pelajar/Mahasiswa',
                'Pensiunan',
                'Pegawai Negeri Sipil',
                'Industri',
                'Kontruksi',
                'Transportasi',
                'Karyawan Swasta',
                'Karyawan BUMN',
                'Karyawan BUMD',
                'Karyawan Honorer',
                'Dosen',
                'Guru',
                'Arsitek',
                'Akuntan',
                'Pialang',
                'Wiraswasta',
                'Lainnya',
            ]);
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('education', ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2'])->nullable();
            $table->enum('role', ['admin', 'instruktur', 'peserta'])->default('peserta');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['email', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};