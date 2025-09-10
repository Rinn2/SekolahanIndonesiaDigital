<?php
// app/Services/Instruktur/ExportService.php

namespace App\Services\Instruktur;

use App\Repositories\Interfaces\ScheduleRepositoryInterface;
use App\Utils\CsvExporter;
use App\Utils\StatusHelper;
use App\Exceptions\Instruktur\ScheduleNotFoundException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    protected $scheduleRepository;
    protected $csvExporter;
    protected $statusHelper;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepository,
        CsvExporter $csvExporter,
        StatusHelper $statusHelper
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->csvExporter = $csvExporter;
        $this->statusHelper = $statusHelper;
    }

    public function exportStudents(int $scheduleId, int $instructorId): StreamedResponse
    {
        $schedule = $this->scheduleRepository->findByInstructorAndId($instructorId, $scheduleId);

        if (!$schedule) {
            throw new ScheduleNotFoundException('Jadwal tidak ditemukan');
        }

        $students = $schedule->enrollments->where('status', 'diterima');
        
        $headers = ['Nama', 'Email', 'Program', 'Tanggal Daftar', 'Status', 'Status Pembayaran'];
        $data = [];

        foreach ($students as $enrollment) {
            $data[] = [
                $enrollment->user->name,
                $enrollment->user->email,
                $schedule->program->name,
                $enrollment->created_at->format('d M Y'),
                $this->statusHelper->getStatusText($enrollment->status),
                $enrollment->payment_status ?? 'pending'
            ];
        }

        $filename = 'students_' . $schedule->title . '_' . date('Y-m-d') . '.csv';

        return $this->csvExporter->export($headers, $data, $filename);
    }
}