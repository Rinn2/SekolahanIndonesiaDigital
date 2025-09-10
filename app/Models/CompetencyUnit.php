<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetencyUnit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_code',
        'title',
        'description',
        'category',
    ];

    /**
     * Relasi many-to-many ke model Certificate.
     */
    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'certificate_competency_unit');
    }

    /**
     * Relasi many-to-many ke model Program.
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_competency_unit');
    }

    /**
     * Scope untuk filter berdasarkan kategori.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk mendapatkan semua kategori yang unik.
     */
    public static function getCategories()
    {
        return self::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * Accessor untuk mendapatkan kode dan judul dalam format yang mudah dibaca.
     */
    public function getFullTitleAttribute()
    {
        return $this->unit_code . ' - ' . $this->title;
    }
}