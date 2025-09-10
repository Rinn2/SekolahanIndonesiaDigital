<?php
// app/Utils/DateHelper.php

namespace App\Utils;

use Carbon\Carbon;

class DateHelper
{
    public function isActive(Carbon $startDate, Carbon $endDate): bool
    {
        return $startDate->lte(now()) && $endDate->gte(now());
    }

    public function isCompleted(Carbon $endDate): bool
    {
        return $endDate->lt(now());
    }

    public function isUpcoming(Carbon $startDate): bool
    {
        return $startDate->gt(now());
    }

    public function getScheduleStatus(Carbon $startDate, Carbon $endDate): string
    {
        if ($this->isUpcoming($startDate)) {
            return 'upcoming';
        }
        
        if ($this->isActive($startDate, $endDate)) {
            return 'active';
        }
        
        if ($this->isCompleted($endDate)) {
            return 'completed';
        }
        
        return 'unknown';
    }

    public function formatDateRange(Carbon $startDate, Carbon $endDate): string
    {
        if ($startDate->isSameDay($endDate)) {
            return $startDate->format('d M Y');
        }
        
        if ($startDate->isSameMonth($endDate)) {
            return $startDate->format('d') . ' - ' . $endDate->format('d M Y');
        }
        
        return $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');
    }

    public function getRelativeTime(Carbon $date): string
    {
        return $date->diffForHumans();
    }
}