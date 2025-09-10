<?php

namespace App\Models;
use App\Http\Requests\Admin\CreateProgramRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'duration_months',
        'max_participants',
        'price',
        'status',
        'is_active',
        'level',
        'created_by',
        'total_meetings',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function competencyUnits()
    {
        return $this->hasMany(CompetencyUnit::class);
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false)
                     ->orWhere('status', 'tidak_aktif');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function getCurrentEnrollmentCount()
    {
        return $this->enrollments()
                    ->whereIn('status', ['pending', 'diterima'])
                    ->count();
    }

    public function getAvailableSlots()
    {
        return $this->max_participants - $this->getCurrentEnrollmentCount();
    }

    public function isAvailable()
    {
        return $this->is_active &&
               $this->status === 'aktif' &&
               $this->getAvailableSlots() > 0;
    }
}
