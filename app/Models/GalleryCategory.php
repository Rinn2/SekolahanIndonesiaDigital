<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'category_id');
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? '<span class="badge badge-success">Aktif</span>' 
            : '<span class="badge badge-danger">Nonaktif</span>';
    }

    // Mutator untuk memastikan nilai boolean
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}