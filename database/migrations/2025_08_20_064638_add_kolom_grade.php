<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('notes');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->foreign('updated_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop foreign key constraints terlebih dahulu
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Drop kolom created_by dan updated_by
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
