<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_id',
        'instructor_id',
        'title',
        'is_active',
        'description',
        'start_date',
        'end_date',
        'location',
        'max_participants',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function program()
{
    return $this->belongsTo(Program::class)->withDefault([
        'name' => '[Program dihapus]',
        'description' => '',
        'duration_months' => 0,
        'price' => 0,
        'level' => '-',
    ]);
}


    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function isUpcoming()
    {
        return $this->start_date > now();
    }

    public function isOngoing()
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    public function isCompleted()
    {
        return $this->end_date < now();
    }
}
