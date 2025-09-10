<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'meeting_number',
        'grade',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'grade' => 'decimal:2',
    ];

    /* =======================
       🔗 Relasi
    ======================= */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* =======================
       📊 Scope
    ======================= */
    public function scopeByMeeting($query, int $meetingNumber)
    {
        return $query->where('meeting_number', $meetingNumber);
    }

    public function scopeByEnrollment($query, int $enrollmentId)
    {
        return $query->where('enrollment_id', $enrollmentId);
    }

    /* =======================
       🎯 Helper
    ======================= */
    // Apakah nilai lulus
    public function isPassing(): bool
    {
        return $this->grade >= 75;
    }

    // Konversi nilai ke huruf
    public function getGradeLetterAttribute(): string
    {
        if ($this->grade >= 90) return 'A';
        if ($this->grade >= 80) return 'B';
        if ($this->grade >= 75) return 'C';
        if ($this->grade >= 60) return 'D';
        return 'E';
    }
}
