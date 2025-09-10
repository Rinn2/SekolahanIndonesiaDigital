<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        // Relasi utama
        'user_id',
        'program_id',
        'schedule_id',

        // Akademik
        'status',
        'final_grade',
        'completion_date',
        'notes',

        // Pembayaran
        'order_id',
        'snap_token',
        'payment_status',
        'paid_at',
        'enrolled_at',
    ];

    protected $casts = [
        'completion_date' => 'datetime',
        'final_grade'     => 'decimal:2',
        'paid_at'         => 'datetime',
        'enrolled_at'     => 'datetime',
    ];

    /* =======================
       🔗 Relasi
    ======================= */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /* =======================
       📊 Scope Akademik
    ======================= */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['lulus', 'dropout']);
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'lulus');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'dropout');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'diterima');
    }

    /* =======================
       📊 Scope Pembayaran
    ======================= */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('payment_status', 'processing');
    }

    public function scopeFailedPayment($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /* =======================
       🎯 Helper Status Akademik
    ======================= */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending'   => 'Menunggu',
            'diterima'  => 'Diterima',
            'ditolak'   => 'Ditolak',
            'lulus'     => 'Lulus',
            'dropout'   => 'Dropout',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['lulus', 'dropout']);
    }

    public function hasPassed(): bool
    {
        return $this->status === 'lulus';
    }

    /* =======================
       🎯 Helper Status Pembayaran
    ======================= */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPendingPayment(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isProcessingPayment(): bool
    {
        return $this->payment_status === 'processing';
    }

    public function isFailedPaymentStatus(): bool
    {
        return $this->payment_status === 'failed';
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'      => 'green',
            'processing'=> 'blue',
            'pending'   => 'yellow',
            'failed'    => 'red',
            default     => 'gray',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'      => 'Berhasil',
            'processing'=> 'Diproses',
            'pending'   => 'Menunggu',
            'failed'    => 'Gagal',
            default     => 'Tidak Diketahui',
        };
    }

    /* =======================
       ⚡ Event Hooks
    ======================= */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($enrollment) {
            // Set tanggal paid_at otomatis ketika status berubah jadi paid
            if ($enrollment->isDirty('payment_status') && $enrollment->payment_status === 'paid') {
                $enrollment->paid_at = now();
            }
        });
    }
}
