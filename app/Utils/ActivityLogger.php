<?php
// app/Utils/ActivityLogger.php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    public function log(string $action, string $description, array $context = []): void
    {
        $logData = [
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'timestamp' => now(),
            'context' => $context
        ];

        Log::info('Instructor Activity', $logData);

        // You can also store in database if you have an activity log table
        // ActivityLog::create($logData);
    }

    public function logError(string $action, string $error, array $context = []): void
    {
        $logData = [
            'user_id' => auth()->id(),
            'action' => $action,
            'error' => $error,
            'timestamp' => now(),
            'context' => $context
        ];

        Log::error('Instructor Error', $logData);
    }
}
