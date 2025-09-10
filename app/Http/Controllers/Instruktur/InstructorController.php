<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instruktur\UpdateScheduleRequest;
use App\Http\Requests\Instruktur\UpdateEnrollmentRequest;
use App\Http\Requests\Instruktur\StoreGradeRequest;
use App\Services\Instruktur\DashboardService;
use App\Services\Instruktur\ScheduleService;
use App\Services\Instruktur\EnrollmentService;
use App\Services\Instruktur\GradeService;
use App\Services\Instruktur\ExportService;
use App\Exceptions\BaseInstructorException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorController extends Controller
{
    protected $dashboardService;
    protected $scheduleService;
    protected $enrollmentService;
    protected $gradeService;
    protected $exportService;

    public function __construct(
        DashboardService $dashboardService,
        ScheduleService $scheduleService,
        EnrollmentService $enrollmentService,
        GradeService $gradeService,
        ExportService $exportService
    ) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'instruktur') {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });

        $this->dashboardService = $dashboardService;
        $this->scheduleService = $scheduleService;
        $this->enrollmentService = $enrollmentService;
        $this->gradeService = $gradeService;
        $this->exportService = $exportService;
    }

    /**
     * Display  dashboard
     */
    public function index(): View
    {
        try {
            $dashboardData = $this->dashboardService->getDashboardData(Auth::id());
            return view('instruktur.dashboard', $dashboardData);
        } catch (BaseInstructorException $e) {
            return view('instruktur.dashboard', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store or update grade
     */
    public function storeOrUpdateGrade(StoreGradeRequest $request): JsonResponse
    {
        try {
            $grade = $this->gradeService->storeOrUpdateGrade(
                $request->validated(),
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Nilai berhasil disimpan.',
                'data' => $grade
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Get grades for enrollment
     */
    public function getGrades(int $enrollmentId): JsonResponse
    {
        try {
            $grades = $this->gradeService->getGrades($enrollmentId, Auth::id());

            return response()->json([
                'success' => true,
                'data' => $grades
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Get schedule details
     */
    public function getSchedule(int $id): JsonResponse
    {
        try {
            $schedule = $this->scheduleService->getSchedule($id, Auth::id());

            return response()->json([
                'success' => true,
                'data' => $schedule
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Update schedule
     */
    public function updateSchedule(UpdateScheduleRequest $request, int $id): JsonResponse
    {
        try {
            $updated = $this->scheduleService->updateSchedule(
                $id,
                Auth::id(),
                $request->validated()
            );

            return response()->json([
                'success' => $updated,
                'message' => $updated ? 'Jadwal berhasil diperbarui' : 'Gagal memperbarui jadwal'
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Update enrollment status (legacy method for backward compatibility)
     */
    public function updateEnrollmentStatus(Request $request, int $id): JsonResponse
    {
        // Convert to UpdateEnrollmentRequest validation
        $validatedData = $request->validate([
            'status' => 'required|in:pending,diterima,ditolak,lulus,dropout',
            'notes' => 'nullable|string'
        ]);

        return $this->updateEnrollmentInternal($validatedData, $id);
    }

    /**
     * Get enrollment data
     */
    public function getEnrollment(int $id): JsonResponse
    {
        try {
            $enrollmentData = $this->enrollmentService->getEnrollment($id, Auth::id());

            return response()->json([
                'success' => true,
                'data' => $enrollmentData
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Update enrollment (enhanced method)
     */
    public function updateEnrollment(UpdateEnrollmentRequest $request, int $id): JsonResponse
    {
        return $this->updateEnrollmentInternal($request->validated(), $id);
    }

    /**
     * Update enrollment with POST method (fallback)
     */
    public function updateEnrollmentPost(UpdateEnrollmentRequest $request, int $id): JsonResponse
    {
        return $this->updateEnrollmentInternal($request->validated(), $id);
    }

    /**
     * Internal method to handle enrollment updates
     */
    private function updateEnrollmentInternal(array $data, int $id): JsonResponse
    {
        try {
            $enrollmentData = $this->enrollmentService->updateEnrollment($id, Auth::id(), $data);

            return response()->json([
                'success' => true,
                'message' => 'Status enrollment berhasil diperbarui',
                'data' => $enrollmentData
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Get student progress
     */
    public function getStudentProgress(int $scheduleId): JsonResponse
    {
        try {
            $students = $this->scheduleService->getStudentProgress($scheduleId, Auth::id());

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (BaseInstructorException $e) {
            return $e->render();
        }
    }

    /**
     * Export students data
     */
    public function exportStudents(int $scheduleId)
    {
        try {
            return $this->exportService->exportStudents($scheduleId, Auth::id());
        } catch (BaseInstructorException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}