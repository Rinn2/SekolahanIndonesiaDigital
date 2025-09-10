<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class UserDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'pasfoto',
        'ktp',
        'ijazah_terakhir',
        'pasfoto_uploaded_at',
        'ktp_uploaded_at',
        'ijazah_uploaded_at',
    ];

    protected $casts = [
        'pasfoto_uploaded_at' => 'datetime',
        'ktp_uploaded_at' => 'datetime',
        'ijazah_uploaded_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full URL untuk pasfoto
     */
    public function getPasfotoUrlAttribute(): ?string
    {
        return $this->pasfoto ? Storage::url($this->pasfoto) : null;
    }

    /**
     * Get full URL untuk KTP
     */
    public function getKtpUrlAttribute(): ?string
    {
        return $this->ktp ? Storage::url($this->ktp) : null;
    }

    /**
     * Get full URL untuk ijazah
     */
    public function getIjazahUrlAttribute(): ?string
    {
        return $this->ijazah_terakhir ? Storage::url($this->ijazah_terakhir) : null;
    }

    /**
     * Cek apakah pasfoto sudah diupload
     */
    public function hasPasfoto(): bool
    {
        return !empty($this->pasfoto) && Storage::exists($this->pasfoto);
    }

    /**
     * Cek apakah KTP sudah diupload
     */
    public function hasKtp(): bool
    {
        return !empty($this->ktp) && Storage::exists($this->ktp);
    }

    /**
     * Cek apakah ijazah sudah diupload
     */
    public function hasIjazah(): bool
    {
        return !empty($this->ijazah_terakhir) && Storage::exists($this->ijazah_terakhir);
    }

    /**
     * Cek apakah semua dokumen sudah lengkap
     */
    public function isComplete(): bool
    {
        return $this->hasPasfoto() && $this->hasKtp() && $this->hasIjazah();
    }

    /**
     * Get persentase kelengkapan dokumen
     */
    public function getCompletenessPercentage(): int
    {
        $total = 3;
        $completed = 0;

        if ($this->hasPasfoto()) $completed++;
        if ($this->hasKtp()) $completed++;
        if ($this->hasIjazah()) $completed++;

        return (int) round(($completed / $total) * 100);
    }

    /**
     * Hapus file dokumen dari storage
     */
    public function deleteDocumentFiles(): void
    {
        if ($this->pasfoto && Storage::exists($this->pasfoto)) {
            Storage::delete($this->pasfoto);
        }
        
        if ($this->ktp && Storage::exists($this->ktp)) {
            Storage::delete($this->ktp);
        }
        
        if ($this->ijazah_terakhir && Storage::exists($this->ijazah_terakhir)) {
            Storage::delete($this->ijazah_terakhir);
        }
    }

    /**
     * Event ketika model dihapus
     */
    protected static function boot()
    {
        parent::boot();

        // Hapus file fisik ketika model dihapus (force delete)
        static::forceDeleted(function ($document) {
            $document->deleteDocumentFiles();
        });
    }
}