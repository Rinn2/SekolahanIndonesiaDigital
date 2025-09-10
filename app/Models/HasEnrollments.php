<?php
namespace App\Models\Traits;

use App\Models\Enrollment;

trait HasEnrollments
{
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollments()
    {
        return $this->enrollments()->whereIn('status', ['pending', 'diterima']);
    }

    public function completedEnrollments()
    {
        return $this->enrollments()->whereIn('status', ['lulus', 'dropout']);
    }

    public function hasActiveEnrollment($programId = null)
    {
        $query = $this->activeEnrollments();
        
        if ($programId) {
            $query->where('program_id', $programId);
        }
        
        return $query->exists();
    }
}