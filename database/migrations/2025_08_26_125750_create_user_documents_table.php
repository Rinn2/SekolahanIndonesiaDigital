<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('pasfoto')->nullable()->comment('Path file pasfoto');
            $table->string('ktp')->nullable()->comment('Path file KTP');
            $table->string('ijazah_terakhir')->nullable()->comment('Path file ijazah terakhir');
            $table->timestamp('pasfoto_uploaded_at')->nullable();
            $table->timestamp('ktp_uploaded_at')->nullable();
            $table->timestamp('ijazah_uploaded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performa query
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};