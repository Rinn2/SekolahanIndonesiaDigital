<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'program_id',
        'certificate_number',
        'issue_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Relasi ke model User (sertifikat ini milik siapa).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Program (sertifikat ini untuk program apa).
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Relasi many-to-many ke model CompetencyUnit.
     */
    public function competencyUnits()
    {
        return $this->belongsToMany(CompetencyUnit::class, 'certificate_competency_unit');
    }
}

