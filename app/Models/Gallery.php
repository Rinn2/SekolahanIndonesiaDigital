<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GalleryCategory;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'category_id',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

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

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
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

    public function scopePublished($query)
    {
        return $query->where('is_active', true)->whereNotNull('image');
    }

    // Accessor untuk URL gambar - UPDATED untuk mendukung berbagai folder
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/placeholder.jpg');
        }
                
        // Jika sudah full URL, return as is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Cek apakah path sudah lengkap dengan folder
        if (str_starts_with($this->image, 'gallery/') || 
            str_starts_with($this->image, 'images/') || 
            str_starts_with($this->image, 'uploads/')) {
            return asset('storage/' . $this->image);
        }
        
        // Jika hanya nama file, coba cari di folder gallery terlebih dahulu
        $galleryPath = 'gallery/' . $this->image;
        if (file_exists(storage_path('app/public/' . $galleryPath))) {
            return asset('storage/' . $galleryPath);
        }
        
        // Fallback ke images folder
        $imagesPath = 'images/' . $this->image;
        if (file_exists(storage_path('app/public/' . $imagesPath))) {
            return asset('storage/' . $imagesPath);
        }
                
        // Jika tidak ditemukan di mana-mana, return path asli dengan prefix storage
        return asset('storage/' . $this->image);
    }

    // Accessor untuk thumbnail URL
    public function getThumbnailUrlAttribute()
    {
        return $this->image_url;
    }
}