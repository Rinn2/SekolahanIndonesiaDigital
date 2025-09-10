<?php
// app/Http/Controllers/Instruktur/GradeController.php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instruktur\StoreGradeRequest;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Services\GradeService;
use Illuminate\Http\JsonResponse;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }


    public function store(StoreGradeRequest $request): JsonResponse
    {
        try {
            // Pastikan enrollment milik instruktur yang sedang login
            $enrollment = Enrollment::with(['schedule', 'program'])
                ->whereHas('schedule', function ($query) {
                    $query->where('instructor_id', auth()->id());
                })
                ->findOrFail($request->enrollment_id);

            // Cek apakah nilai untuk pertemuan ini sudah ada
            $existingGrade = Grade::where('enrollment_id', $request->enrollment_id)
                ->where('meeting_number', $request->meeting_number)
                ->first();

            if ($existingGrade) {
                $existingGrade->update([
                    'grade' => $request->grade,
                    'notes' => $request->notes,
                    'updated_by' => auth()->id()
                ]);
                $grade = $existingGrade;
            } else {
                $grade = Grade::create([
                    'enrollment_id' => $request->enrollment_id,
                    'meeting_number' => $request->meeting_number,
                    'grade' => $request->grade,
                    'notes' => $request->notes,
                    'created_by' => auth()->id()
                ]);
            }

            // Cek dan update status enrollment jika semua pertemuan sudah selesai
            $statusUpdated = $this->gradeService->updateEnrollmentStatus($request->enrollment_id);
            
            $message = 'Nilai berhasil disimpan.';
            if ($statusUpdated) {
                $enrollment->refresh();
                $finalGrade = $this->gradeService->calculateFinalGrade($request->enrollment_id);
                
                if ($enrollment->status === 'lulus') {
                    $message .= " Selamat! Siswa telah lulus dengan nilai akhir {$finalGrade}.";
                } elseif ($enrollment->status === 'ditolak' || $enrollment->status === 'dropout') {
                    $message .= " Siswa tidak lulus dengan nilai akhir {$finalGrade}.";
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'grade' => $grade,
                    'status_updated' => $statusUpdated,
                    'enrollment_status' => $enrollment->status ?? null,
                    'final_grade' => $statusUpdated ? $this->gradeService->calculateFinalGrade($request->enrollment_id) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGradesByEnrollment(int $enrollmentId): JsonResponse
    {
        try {
            $enrollment = Enrollment::with(['schedule', 'program'])
                ->whereHas('schedule', function ($query) {
                    $query->where('instructor_id', auth()->id());
                })
                ->findOrFail($enrollmentId);

            $grades = Grade::where('enrollment_id', $enrollmentId)
                ->orderBy('meeting_number')
                ->get();

            $progress = $this->gradeService->getStudentProgress($enrollmentId);

            return response()->json([
                'success' => true,
                'data' => $grades,
                'progress' => $progress,
                'enrollment' => [
                    'status' => $enrollment->status,
                    'final_grade' => $enrollment->final_grade
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function calculateFinalGrades(): JsonResponse
    {
        try {
            $readyForEvaluation = $this->gradeService->getStudentsReadyForFinalEvaluation(auth()->id());
            $updatedCount = 0;

            foreach ($readyForEvaluation as $item) {
                $enrollment = $item['enrollment'];
                if ($enrollment->status === 'diterima') {
                    $this->gradeService->updateEnrollmentStatus($enrollment->id);
                    $updatedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil memproses {$updatedCount} siswa untuk evaluasi akhir.",
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getStudentsReadyForEvaluation(): JsonResponse
    {
        try {
            $students = $this->gradeService->getStudentsReadyForFinalEvaluation(auth()->id());

            return response()->json([
                'success' => true,
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentProgress(int $enrollmentId): JsonResponse
    {
        try {
            Enrollment::whereHas('schedule', function ($query) {
                $query->where('instructor_id', auth()->id());
            })->findOrFail($enrollmentId);

            $progress = $this->gradeService->getStudentProgress($enrollmentId);

            return response()->json([
                'success' => true,
                'data' => $progress
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
