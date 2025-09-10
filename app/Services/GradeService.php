<?php
// app/Services/GradeService.php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class GradeService
{
    /**
     * Menghitung nilai akhir siswa berdasarkan semua nilai yang telah diinput
     */
    public function calculateFinalGrade(int $enrollmentId): float
    {
        $grades = Grade::where('enrollment_id', $enrollmentId)->get();
        
        if ($grades->isEmpty()) {
            return 0;
        }

        // Hitung rata-rata dari semua nilai
        $totalGrades = $grades->sum('grade');
        $countGrades = $grades->count();
        
        return round($totalGrades / $countGrades, 2);
    }

    /**
     * Update status enrollment berdasarkan nilai akhir
     */
    public function updateEnrollmentStatus(int $enrollmentId): bool
    {
        $enrollment = Enrollment::with(['program', 'grades'])->find($enrollmentId);
        
        if (!$enrollment) {
            return false;
        }

        // Dapatkan jumlah pertemuan yang seharusnya untuk program ini
        $expectedMeetings = $enrollment->program->total_meetings ?? 8; // default 8 pertemuan
        
        // Hitung berapa pertemuan yang sudah dinilai
        $completedMeetings = $enrollment->grades->count();
        
        // Jika semua pertemuan sudah dinilai, hitung nilai akhir
        if ($completedMeetings >= $expectedMeetings) {
            $finalGrade = $this->calculateFinalGrade($enrollmentId);
            
            // Update status berdasarkan nilai akhir
            $newStatus = $finalGrade >= 75 ? 'lulus' : 'tidak_lulus';
            
            $enrollment->update([
                'status' => $newStatus,
                'final_grade' => $finalGrade,
                'completion_date' => now()
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Cek apakah siswa sudah menyelesaikan semua pertemuan
     */
    public function isAllMeetingsCompleted(int $enrollmentId): bool
    {
        $enrollment = Enrollment::with(['program', 'grades'])->find($enrollmentId);
        
        if (!$enrollment) {
            return false;
        }

        $expectedMeetings = $enrollment->program->total_meetings ?? 8;
        $completedMeetings = $enrollment->grades->count();
        
        return $completedMeetings >= $expectedMeetings;
    }

    /**
     * Mendapatkan progress siswa dalam bentuk persentase
     */
    public function getStudentProgress(int $enrollmentId): array
    {
        $enrollment = Enrollment::with(['program', 'grades'])->find($enrollmentId);
        
        if (!$enrollment) {
            return [
                'progress_percentage' => 0,
                'completed_meetings' => 0,
                'total_meetings' => 0,
                'average_grade' => 0,
                'final_grade' => null
            ];
        }

        $expectedMeetings = $enrollment->program->total_meetings ?? 8;
        $completedMeetings = $enrollment->grades->count();
        $progressPercentage = ($completedMeetings / $expectedMeetings) * 100;
        
        $averageGrade = $completedMeetings > 0 ? 
            round($enrollment->grades->avg('grade'), 2) : 0;
        
        $finalGrade = $this->isAllMeetingsCompleted($enrollmentId) ? 
            $this->calculateFinalGrade($enrollmentId) : null;

        return [
            'progress_percentage' => round($progressPercentage, 2),
            'completed_meetings' => $completedMeetings,
            'total_meetings' => $expectedMeetings,
            'average_grade' => $averageGrade,
            'final_grade' => $finalGrade
        ];
    }

    /**
     * Mendapatkan siswa yang siap untuk evaluasi akhir
     */
    public function getStudentsReadyForFinalEvaluation(int $instructorId): array
    {
        return Enrollment::whereHas('schedule', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->with(['user', 'program', 'schedule', 'grades'])
            ->get()
            ->filter(function ($enrollment) {
                return $this->isAllMeetingsCompleted($enrollment->id) && 
                       $enrollment->status === 'diterima';
            })
            ->map(function ($enrollment) {
                $progress = $this->getStudentProgress($enrollment->id);
                return [
                    'enrollment' => $enrollment,
                    'progress' => $progress
                ];
            })
            ->toArray();
    }
}