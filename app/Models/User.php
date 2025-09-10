<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasOne;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, MustVerifyEmailTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'phone',
        'address',
        'birth_date',
        'gender',
        'education',
        'programstudi',
        'role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    //======================================================================
    // RELASI DATABASE - FIXED
    //======================================================================

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * PERBAIKAN: Relasi ke programs melalui enrollments
     * Menggunakan table alias untuk menghindari ambiguitas
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'enrollments', 'user_id', 'program_id')
                    ->withPivot('status', 'created_at', 'updated_at')
                    ->withTimestamps();
    }

    /**
     * SOLUSI FINAL: Get programs yang sudah diselesaikan user
     * Menggunakan Raw Query untuk menghindari ambiguitas sepenuhnya
     */
    public function completedPrograms()
    {
        $programIds = DB::table('enrollments')
            ->where('user_id', $this->id)
            ->where('status', 'lulus')
            ->pluck('program_id');

        return Program::whereIn('id', $programIds)->get();
    }

    /**
     * Get enrollments yang sudah diterima
     */
    public function acceptedEnrollments()
    {
        return $this->enrollments()->where('status', 'diterima');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function createdPrograms()
    {
        return $this->hasMany(Program::class, 'created_by');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'instructor_id');
    }

    //======================================================================
    // untuk menghindari ambiguitas
    //======================================================================

    public function scopeRole($query, $role)
    {
        return $query->where('users.role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('users.role', 'admin');
    }

    public function scopeInstructors($query)
    {
        return $query->where('users.role', 'instruktur');
    }

    public function scopeParticipants($query)
    {
        return $query->whereIn('users.role', ['peserta', 'participant']);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('users.email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('users.email_verified_at');
    }

    /**
     * untuk user yang telah menyelesaikan program tertentu
     * Menggunakan subquery untuk menghindari ambiguitas
     */
    public function scopeCompletedProgram($query, $programId)
    {
        return $query->whereIn('id', function($subQuery) use ($programId) {
            $subQuery->select('user_id')
                     ->from('enrollments')
                     ->where('program_id', $programId)
                     ->where('status', 'lulus');
        });
    }

    /**
     *  untuk user yang enrolled di program dengan status tertentu
     */
    public function scopeEnrolledInProgram($query, $programId, $status = null)
    {
        return $query->whereIn('id', function($subQuery) use ($programId, $status) {
            $subQuery->select('user_id')
                     ->from('enrollments')
                     ->where('program_id', $programId);
            
            if ($status) {
                $subQuery->where('status', $status);
            }
        });
    }

    /**
     * FIXED: Scope yang menggunakan join dengan alias eksplisit
     */
    public function scopeGraduatedFromProgram($query, $programId)
    {
        return $query->select('users.*')
                     ->join('enrollments as e', 'users.id', '=', 'e.user_id')
                     ->where('e.program_id', $programId)
                     ->where('e.status', 'lulus')
                     ->distinct();
    }

    //======================================================================
    // ACCESSORS & MUTATORS
    //======================================================================

    public function getIsVerifiedAttribute()
    {
        return $this->hasVerifiedEmail();
    }

    public function getGenderDisplayAttribute()
    {
        return match($this->gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => 'Tidak Diketahui'
        };
    }

    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'instruktur' => 'Instruktur',
            'peserta', 'participant' => 'Peserta',
            default => ucfirst($this->role)
        };
    }
    
    public function getDashboardUrlAttribute()
    {
        return match($this->role) {
            'admin' => '/admin/dashboard',
            'instruktur' => '/instruktur/dashboard',
            'peserta', 'participant' => '/dashboard/peserta',
            default => '/dashboard'
        };
    }

    public function setNikAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['nik'] = null;
        } else {
            $this->attributes['nik'] = preg_replace('/[^0-9]/', '', $value);
        }
    }

    //======================================================================
    // HELPERS -  untuk menghindari ambiguitas
    //======================================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstruktur(): bool
    {
        return $this->role === 'instruktur';
    }

    public function isPeserta(): bool
    {
        return in_array($this->role, ['peserta', 'participant']);
    }

    /**
     * Cek apakah user punya sertifikat untuk program tertentu
     */
    public function hasCertificateForProgram($programId): bool
    {
        return $this->certificates()->where('program_id', $programId)->exists();
    }

    /**
     * mengambil sertifikat untuk program tertentu
     */
    public function getCertificateForProgram($programId)
    {
        return $this->certificates()->where('program_id', $programId)->first();
    }

    /**
     * cek  user sudah menyelesaikan program tertentu
     * Menggunakan raw query untuk menghindari ambiguitas
     */
    public function hasCompletedProgram($programId): bool
    {
        return DB::table('enrollments')
                 ->where('user_id', $this->id)
                 ->where('program_id', $programId)
                 ->where('status', 'lulus')
                 ->exists();
    }

    /**
     *  enrollment untuk program tertentu
     */
    public function getEnrollmentForProgram($programId)
    {
        return $this->enrollments()
                    ->where('program_id', $programId)
                    ->first();
    }

    /**
     *  status enrollment untuk program tertentu
     */
    public function getEnrollmentStatusForProgram($programId)
    {
        $enrollment = $this->getEnrollmentForProgram($programId);
        return $enrollment ? $enrollment->status : null;
    }

    /**
     * Method untuk mendapatkan semua program dengan status enrollment
     */
    public function getAllProgramsWithStatus()
    {
        return DB::table('programs as p')
                 ->leftJoin('enrollments as e', function($join) {
                     $join->on('p.id', '=', 'e.program_id')
                          ->where('e.user_id', $this->id);
                 })
                 ->select([
                     'p.*',
                     'e.status as enrollment_status',
                     'e.created_at as enrolled_at'
                 ])
                 ->whereNull('p.deleted_at')
                 ->get();
    }

    /**
     *  Cari users yang sudah lulus dari program tertentu
     */
    public static function getGraduatedUsers($programId)
    {
        return self::select('users.*')
                   ->join('enrollments', 'users.id', '=', 'enrollments.user_id')
                   ->where('enrollments.program_id', $programId)
                   ->where('enrollments.status', 'lulus')
                   ->whereNull('users.deleted_at')
                   ->distinct()
                   ->get();
    }

    /**
     * STATIC METHOD: Alternatif menggunakan Raw SQL
     */
    public static function getGraduatedUsersRaw($programId)
    {
        $sql = "
            SELECT DISTINCT u.* 
            FROM users u
            INNER JOIN enrollments e ON u.id = e.user_id
            WHERE e.program_id = ? 
            AND e.status = 'lulus'
            AND u.deleted_at IS NULL
        ";
        
        $results = DB::select($sql, [$programId]);
        
        // Convert to Collection of User models
        return collect($results)->map(function($userData) {
            $user = new self();
            foreach ($userData as $key => $value) {
                $user->setAttribute($key, $value);
            }
            $user->exists = true;
            return $user;
        });
    }
    public function documents(): HasOne
{
    return $this->hasOne(UserDocument::class);
}

/**
 * Get atau buat dokumen user
 */
public function getOrCreateDocuments(): UserDocument
{
    if (!$this->documents) {
        return $this->documents()->create([]);
    }
    
    return $this->documents;
}

    /**
     * Check if the user has a specific document uploaded.
     *
     * @param string $type
     * @return bool
     */
    public function hasDocument($type)
    {
        return !empty($this->documents) && !empty($this->documents->{$type});
    }
/**
 * Get URL for a specific document
 */
public function getDocumentUrl(string $documentType): ?string
{
    if (!$this->hasDocument($documentType)) {
        return null;
    }
    
    $documentPath = $this->documents->{$documentType};
    
    // If it's already a full URL, return as is
    if (filter_var($documentPath, FILTER_VALIDATE_URL)) {
        return $documentPath;
    }
    
    // Otherwise, assume it's a storage path and generate URL
    return asset('storage/' . $documentPath);
}

/**
 * Get all document types that user has uploaded
 */
public function getUploadedDocuments(): array
{
    if (!$this->documents) {
        return [];
    }
    
    $uploaded = [];
    $documentTypes = ['pasfoto', 'ktp', 'ijazah', 'cv']; // Add more types as needed
    
    foreach ($documentTypes as $type) {
        if (!empty($this->documents->{$type})) {
            $uploaded[] = $type;
        }
    }
    
    return $uploaded;
}
/**
 * Cek apakah user sudah upload semua dokumen
 */
public function hasCompleteDocuments(): bool
{
    return $this->documents && $this->documents->isComplete();
}

/**
 * Get persentase kelengkapan dokumen user
 */
public function getDocumentCompletenessPercentage(): int
{
    return $this->documents ? $this->documents->getCompletenessPercentage() : 0;
}
}