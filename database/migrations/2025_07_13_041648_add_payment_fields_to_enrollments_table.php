<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'order_id')) {
                $table->string('order_id')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('enrollments', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('order_id');
            }

            if (!Schema::hasColumn('enrollments', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'processing', 'paid', 'failed', 'challenge', 'free'])
                      ->default('pending')
                      ->after('snap_token');
            }

            if (!Schema::hasColumn('enrollments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token', 'payment_status', 'paid_at']);
        });
    }
};
