<?php
// app/Services/Instruktur/EnrollmentService.php

namespace App\Services\Instruktur;

use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Exceptions\Instruktur\EnrollmentNotFoundException;
use App\Utils\ActivityLogger;
use App\Utils\StatusHelper;
use App\Models\Enrollment;

class EnrollmentService
{
    protected $enrollmentRepository;
    protected $activityLogger;
    protected $statusHelper;

    public function __construct(
        EnrollmentRepositoryInterface $enrollmentRepository,
        ActivityLogger $activityLogger,
        StatusHelper $statusHelper
    ) {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->activityLogger = $activityLogger;
        $this->statusHelper = $statusHelper;
    }

    public function getEnrollment(int $enrollmentId, int $instructorId): array
    {
        $enrollment = $this->enrollmentRepository->getEnrollmentWithRelations($enrollmentId, $instructorId);

        if (!$enrollment) {
            throw new EnrollmentNotFoundException('Enrollment tidak ditemukan');
        }

        return $this->formatEnrollmentData($enrollment);
    }

    public function updateEnrollment(int $enrollmentId, int $instructorId, array $data): array
    {
        $enrollment = $this->enrollmentRepository->getEnrollmentWithRelations($enrollmentId, $instructorId);

        if (!$enrollment) {
            throw new EnrollmentNotFoundException('Enrollment tidak ditemukan atau Anda tidak memiliki akses');
        }

        $oldStatus = $enrollment->status;
        
        $updateData = [
            'status' => $data['status'],
            'updated_at' => now()
        ];

        if (isset($data['notes'])) {
            $updateData['notes'] = $data['notes'];
        }

        $updated = $this->enrollmentRepository->updateEnrollment($enrollment, $updateData);

        if ($updated) {
            $enrollment = $enrollment->fresh(['user', 'program', 'schedule']);
            $this->activityLogger->log('update_enrollment', 
                "Mengubah status enrollment {$enrollment->user->name} dari {$oldStatus} menjadi {$data['status']}");
        }

        return $this->formatEnrollmentData($enrollment);
    }

    protected function formatEnrollmentData(Enrollment $enrollment): array
    {
        return [
            'id' => $enrollment->id,
            'user_id' => $enrollment->user_id,
            'program_id' => $enrollment->program_id,
            'schedule_id' => $enrollment->schedule_id,
            'status' => $enrollment->status,
            'status_text' => $this->statusHelper->getStatusText($enrollment->status),
            'payment_status' => $enrollment->payment_status ?? 'pending',
            'notes' => $enrollment->notes ?? '',
            'created_at' => $enrollment->created_at,
            'updated_at' => $enrollment->updated_at,
            'user' => [
                'id' => $enrollment->user->id,
                'name' => $enrollment->user->name,
                'email' => $enrollment->user->email,
            ],
            'program' => [
                'id' => $enrollment->program->id,
                'name' => $enrollment->program->name,
                'description' => $enrollment->program->description ?? '',
            ],
            'schedule' => [
                'id' => $enrollment->schedule->id,
                'title' => $enrollment->schedule->title,
                'start_date' => $enrollment->schedule->start_date,
                'end_date' => $enrollment->schedule->end_date,
                'location' => $enrollment->schedule->location ?? '',
            ]
        ];
    }
}
