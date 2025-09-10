<?php
namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment; 
use App\Models\Certificate; 

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua pendaftaran (enrollment) milik user
        $enrollments = $user->enrollments()
                            ->with(['program', 'schedule.instructor', 'grades'])
                            ->latest()
                            ->get();

        // 2. Ambil semua sertifikat milik user
        $certificates = Certificate::where('user_id', $user->id)
                            ->with('program')
                            ->orderBy('issue_date', 'desc')
                            ->get();

        // Hitung statistik untuk kartu
        $totalPrograms = $enrollments->count();
        $activePrograms = $enrollments->whereIn('status', ['diterima', 'berjalan'])->count();
        $completedPrograms = $enrollments->where('status', 'lulus')->count();

        // Hitung progres rata-rata
        $totalProgress = 0;
        if ($enrollments->isNotEmpty()) {
            foreach ($enrollments as $enrollment) {
                $totalMeetings = $enrollment->program->total_meetings ?? 10; 
                $completedMeetings = $enrollment->grades->count();
                $progress = $totalMeetings > 0 ? ($completedMeetings / $totalMeetings) * 100 : 0;
                $totalProgress += $progress;
            }
            $averageProgress = round($totalProgress / $enrollments->count());
        } else {
            $averageProgress = 0;
        }

        return view('peserta.dashboard', [
            'enrollments' => $enrollments,
            'totalPrograms' => $totalPrograms,
            'activePrograms' => $activePrograms,
            'completedPrograms' => $completedPrograms,
            'averageProgress' => $averageProgress,
            'certificates' => $certificates, 
        ]);
    }
}
