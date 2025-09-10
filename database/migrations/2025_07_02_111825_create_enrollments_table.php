<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->onDelete('set null');
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'lulus', 'dropout'])->default('pending');
            $table->datetime('enrollment_date')->default(now());
            $table->datetime('completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('order_id')->nullable(); // ✅ tanpa ->after('notes')
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'program_id']);
            $table->index(['user_id', 'status']);
            $table->index('program_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
