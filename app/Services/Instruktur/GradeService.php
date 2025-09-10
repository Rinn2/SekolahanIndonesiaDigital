<?php
// app/Services/Instruktur/GradeService.php

namespace App\Services\Instruktur;

use App\Repositories\Interfaces\GradeRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Exceptions\Instruktur\EnrollmentNotFoundException;
use App\Exceptions\Instruktur\UnauthorizedAccessException;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Collection;

class GradeService
{
    protected $gradeRepository;
    protected $enrollmentRepository;

    public function __construct(
        GradeRepositoryInterface $gradeRepository,
        EnrollmentRepositoryInterface $enrollmentRepository
    ) {
        $this->gradeRepository = $gradeRepository;
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function storeOrUpdateGrade(array $data, int $instructorId): Grade
    {
        $enrollment = $this->enrollmentRepository->findByInstructorAndId($instructorId, $data['enrollment_id']);

        if (!$enrollment) {
            throw new EnrollmentNotFoundException('Enrollment tidak ditemukan atau Anda tidak punya akses.');
        }

        $conditions = [
            'enrollment_id' => $data['enrollment_id'],
            'meeting_number' => $data['meeting_number'],
        ];

        $gradeData = [
            'grade' => $data['grade'],
            'notes' => $data['notes'] ?? null,
        ];

        return $this->gradeRepository->updateOrCreateGrade($conditions, $gradeData);
    }

    public function getGrades(int $enrollmentId, int $instructorId): Collection
    {
        $enrollment = $this->enrollmentRepository->findByInstructorAndId($instructorId, $enrollmentId);

        if (!$enrollment) {
            throw new UnauthorizedAccessException('Akses ditolak.');
        }

        return $this->gradeRepository->getGradesByEnrollment($enrollmentId);
    }
}
