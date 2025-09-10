<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_months');
            $table->integer('max_participants');
            $table->decimal('price', 12, 2)->nullable();

            $table->enum('status', ['aktif', 'tidak_aktif', 'selesai'])->default('aktif');
            $table->boolean('is_active')->default(true);

            // ✅ Tambahan kolom 'level'
            $table->enum('level', ['Pemula', 'Menengah', 'Lanjutan'])->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_active']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
