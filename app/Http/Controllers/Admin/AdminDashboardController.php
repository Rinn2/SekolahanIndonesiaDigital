<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Enrollment;
use App\Models\Gallery;
use App\Models\Certificate;
use App\Models\CompetencyUnit;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Requests\Admin\GetUserRequest;
use App\Http\Requests\Admin\CreateProgramRequest;
use App\Http\Requests\Admin\GetProgramRequest;
use App\Http\Requests\Admin\UpdateProgramRequest;
use App\Http\Requests\Admin\CreateScheduleRequest;
use App\Http\Requests\Admin\UpdateScheduleRequest; 
use App\Http\Requests\Admin\DeleteEnrollmentRequest;
use App\Http\Requests\Admin\UpdateEnrollmentRequest;
use App\Services\Admin\DashboardService;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\GalleryCategory;
use App\Http\Requests\Admin\CreateGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Http\Requests\Admin\StoreGalleryCategoryRequest;
use App\Http\Requests\Admin\UpdateGalleryCategoryRequest;
use App\Http\Requests\Admin\StoreGalleryRequest;
class AdminDashboardController extends Controller
{
    private DashboardService $dashboardService;
    private UserService $userService;
    private $galleryService;
    private $categoryService;

    public function __construct(DashboardService $dashboardService, UserService $userService, \App\Services\Admin\GalleryService $galleryService, \App\Services\Admin\GalleryCategoryService $categoryService)
    {
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
        $this->galleryService = $galleryService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $dashboardData = $this->dashboardService->getDashboardData();
        
        if (!isset($dashboardData['galleries'])) {
            $dashboardData['galleries'] = Gallery::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
        $perPage = $request->query('per_page', 10);
        $validPerPages = [10, 20, 30];
        if (!in_array($perPage, $validPerPages)) {
            $perPage = 10;
        }

        // Replace the existing 'users' data with paginated results
        $dashboardData['users'] = User::latest()->paginate($perPage)->withQueryString();
        $dashboardData['perPage'] = $perPage; // Pass the value to the view for dropdown selection

        // Data untuk tab sertifikat
        $dashboardData['certificates'] = Certificate::with(['user', 'program'])->orderBy('issue_date', 'desc')->get();
        $dashboardData['competency_units'] = CompetencyUnit::all();
        $dashboardData['completed_enrollments'] = Enrollment::with(['user', 'program'])
            ->where('status', 'lulus')
            ->get();
        
        // Method 1: Get unique categories from competency_units table
        $dashboardData['categories'] = CompetencyUnit::distinct()->pluck('category')->filter()->sort()->values();
        
        $dashboardData['gallery_categories'] = GalleryCategory::orderBy('name')->get(); 

        return view('admin.dashboard', $dashboardData);
    }

    // ============= GALLERY METHODS =============
       public function storeGallery(StoreGalleryRequest $request)
    {
        $this->galleryService->store($request->validated());
        return response()->json(['success' => true, 'message' => 'Gambar berhasil ditambahkan.']);
    }

    public function updateGallery(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $this->galleryService->update($gallery, $request->validated());
        return response()->json(['success' => true, 'message' => 'Gambar berhasil diperbarui.']);
    }

    public function destroyGallery(Gallery $gallery)
    {
        $this->galleryService->destroy($gallery);
        return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
    }

    // --- CRUD UNTUK KATEGORI GALERI ---
    public function storeCategory(StoreGalleryCategoryRequest $request)
    {
        $this->categoryService->store($request->validated());
        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.']);
    }

    public function updateCategory(UpdateGalleryCategoryRequest $request, GalleryCategory $category)
    {
        $this->categoryService->update($category, $request->validated());
        return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
    }

    public function destroyCategory(GalleryCategory $category)
    {
        $result = $this->categoryService->destroy($category);
        if ($result === null) {
            return response()->json(['success' => false, 'message' => 'Kategori gagal dihapus karena masih digunakan.'], 409);
        }
        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }
    // ============= USER METHODS =============
    public function createUser(CreateUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $userData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'nik' => $validatedData['nik'], 
                'email_verified_at' => now(),
            ];

            Log::info('Creating user with data:', $userData);
            $user = User::create($userData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil dibuat',
                    'data' => $user
                ], 201);
            }

            return redirect()->back()->with('success', 'User berhasil dibuat');
            
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Gagal membuat user: ' . $e->getMessage()], 500)
                : redirect()->back()->withErrors(['error' => 'Gagal membuat user: ' . $e->getMessage()])->withInput();
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan'], 404);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $rules = [
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role' => 'required|string|in:admin,instruktur,peserta',
                'nik' => 'nullable|string|size:16|unique:users,nik,' . $id . '|regex:/^[0-9]{16}$/'
            ];
            
            if ($request->filled('password')) {
                $rules['password'] = 'string|min:8';
            }
            
            $validator = Validator::make($request->all(), $rules, [
                'name.required' => 'Nama harus diisi',
                'name.min' => 'Nama minimal 2 karakter',
                'name.max' => 'Nama maksimal 255 karakter',
                'nik.size' => 'NIK harus tepat 16 digit', 
                'nik.unique' => 'NIK sudah terdaftar',
                'nik.regex' => 'NIK harus berupa 16 digit angka',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.min' => 'Password minimal 8 karakter',
                'role.required' => 'Role harus dipilih',
                'role.in' => 'Role tidak valid'
            ]);

            if ($validator->fails()) {
                return $request->expectsJson()
                    ? response()->json(['success' => false, 'errors' => $validator->errors()], 422)
                    : redirect()->back()->withErrors($validator)->withInput();
            }

            $updateData = [
                'name' => $request->name,
                'email' => strtolower(trim($request->email)),
                'role' => $request->role,
            ];

            if ($request->has('nik')) {
                $nikValue = trim($request->nik);
                if (empty($nikValue)) {
                    $updateData['nik'] = null;
                } else {
                    $cleanNik = preg_replace('/[^0-9]/', '', $nikValue);
                    $updateData['nik'] = empty($cleanNik) ? null : $cleanNik;
                }
            }
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            Log::info('Updating user with data:', $updateData);
            $user->update($updateData);

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'User berhasil diperbarui', 'data' => $user])
                : redirect()->back()->with('success', 'User berhasil diperbarui');
                
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Gagal memperbarui user: ' . $e->getMessage()], 500)
                : redirect()->back()->withErrors(['error' => 'Gagal memperbarui user: ' . $e->getMessage()])->withInput();
        }
    }

    public function deleteUser(DeleteUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'User berhasil dihapus'])
                : redirect()->back()->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500)
                : redirect()->back()->withErrors(['error' => 'Gagal menghapus user: ' . $e->getMessage()])->withInput();
        }
    }

    // ============= PROGRAM METHODS =============
    public function createProgram(CreateProgramRequest $request)
    {
        try {
            $program = Program::create($request->validated());

            return $request->expectsJson()
                ? response()->json(['success' => true, 'message' => 'Program berhasil dibuat', 'data' => $program], 201)
                : redirect()->back()->with('success', 'Program berhasil dibuat');
        } catch (\Exception $e) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Gagal membuat program: ' . $e->getMessage()], 422)
                : redirect()->back()->withErrors(['error' => 'Gagal membuat program: ' . $e->getMessage()])->withInput();
        }
    }

    public function getProgram($id)
    {
        $program = Program::find($id);
        return $program
            ? response()->json(['success' => true, 'data' => $program])
            : response()->json(['success' => false, 'message' => 'Program tidak ditemukan'], 404);
    }

    public function updateProgram(UpdateProgramRequest $request, $id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->update($request->validated());

            return response()->json(['success' => true, 'message' => 'Program berhasil diperbarui', 'data' => $program]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui program: ' . $e->getMessage()], 500);
        }
    }

    public function deleteProgram($id)
    {
        try {
            $program = Program::find($id);

            if (!$program) {
                return response()->json(['success' => false, 'message' => 'Program tidak ditemukan'], 404);
            }

            if ($program->schedules()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Program tidak dapat dihapus karena masih memiliki jadwal. Hapus semua jadwal terlebih dahulu.'], 422);
            }

            if (method_exists($program, 'enrollments')) {
                $activeEnrollments = $program->enrollments()->where('status', '!=', 'ditolak')->count();
                if ($activeEnrollments > 0) {
                    return response()->json(['success' => false, 'message' => 'Program tidak dapat dihapus karena masih memiliki peserta aktif'], 422);
                }
            }

            $program->delete();
            return response()->json(['success' => true, 'message' => 'Program berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus program: ' . $e->getMessage()], 500);
        }
    }

    // ============= SCHEDULE METHODS =============
    public function createSchedule(CreateScheduleRequest $request)
    {
        try {
            $schedule = Schedule::create($request->validated());
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dibuat', 'data' => $schedule], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat jadwal: ' . $e->getMessage()], 500);
        }
    }

    public function getSchedule($id)
    {
        try {
            $schedule = Schedule::find($id);
            return $schedule
                ? response()->json(['success' => true, 'data' => $schedule])
                : response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data jadwal: ' . $e->getMessage()], 500);
        }
    }

    public function updateSchedule(UpdateScheduleRequest $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->update($request->validated());
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui', 'data' => $schedule]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage()], 500);
        }
    }

    public function deleteSchedule($id)
    {
        try {
            $schedule = Schedule::find($id);
            return $schedule
                ? tap($schedule)->delete() && response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus'])
                : response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal: ' . $e->getMessage()], 500);
        }
    }
    
    // ============= ENROLLMENT METHODS =============
    public function deleteEnrollment(DeleteEnrollmentRequest $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();
            return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function updateEnrollment(UpdateEnrollmentRequest $request, $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);
            $validated = $request->validated();

            if (!array_key_exists('status', $validated) || is_null($validated['status']) || trim($validated['status']) === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status field is required.'
                ], 422);
            }

            $updateData = [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ];

            if (in_array($validated['status'], ['lulus', 'dropout'])) {
                $updateData['completion_date'] = now();
            }

            $enrollment->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Status pendaftaran berhasil diperbarui',
                'data' => $enrollment->fresh()->load(['user', 'program', 'schedule'])
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }   
    }

    // ============= GALLERY METHODS =============
    public function createGallery(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'required|url',
                'category' => 'required|string|in:rekrutmen,belajar,keberangkatan,magang,mitra',
                'is_active' => 'boolean'
            ]);

            $validatedData['is_active'] = $validatedData['is_active'] ?? true;
            $gallery = Gallery::create($validatedData);

            return response()->json([
                'success' => true, 
                'message' => 'Gambar galeri berhasil dibuat', 
                'data' => $gallery
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal membuat gambar galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGallery($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            return response()->json(['success' => true, 'data' => $gallery]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gambar galeri tidak ditemukan'], 404);
        }
    }

/**
 * Get competency units by category (alternative method)
 */
/**
 * Get competency units by category (alternative method)
 */
public function getCompetencyUnitsByCategory(Request $request)
{
    try {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'category' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori harus diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = $request->input('category');
        
        Log::info('Fetching competency units for category: ' . $category);

        // Ambil unit kompetensi berdasarkan kategori
        $units = CompetencyUnit::where('category', $category)
                              ->select(['id', 'unit_code', 'title', 'category'])
                              ->orderBy('unit_code', 'asc')
                              ->get();

        Log::info('Found ' . $units->count() . ' units for category: ' . $category);

        // Format response
        $formattedUnits = $units->map(function ($unit) {
            return [
                'id' => $unit->id,
                'unit_code' => $unit->unit_code,
                'title' => $unit->title,
                'category' => $unit->category
            ];
        });

        return response()->json([
            'success' => true,
            'units' => $formattedUnits,
            'count' => $units->count()
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error fetching competency units by category: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data unit kompetensi: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Create certificates in bulk for a program
 */
/**
 * Create certificates in bulk for a program
 */
public function createCertificatesBulk(Request $request)
{
    try {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:programs,id',
            'issue_date' => 'required|date',
            'status' => 'required|string|in:Kompeten,Belum Kompeten',
            'competency_unit_ids' => 'required|array|min:1',
            'competency_unit_ids.*' => 'exists:competency_units,id'
        ], [
            'program_id.required' => 'Program harus dipilih',
            'program_id.exists' => 'Program tidak valid',
            'issue_date.required' => 'Tanggal terbit harus diisi',
            'issue_date.date' => 'Format tanggal tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'competency_unit_ids.required' => 'Minimal satu unit kompetensi harus dipilih',
            'competency_unit_ids.min' => 'Minimal satu unit kompetensi harus dipilih',
            'competency_unit_ids.*.exists' => 'Unit kompetensi tidak valid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $programId = $request->program_id;
        $issueDate = $request->issue_date;
        $status = $request->status;
        $competencyUnitIds = $request->competency_unit_ids;

        // Ambil semua enrollment yang lulus dari program ini
        $completedEnrollments = Enrollment::with(['user', 'program'])
            ->where('program_id', $programId)
            ->where('status', 'lulus')
            ->get();

        if ($completedEnrollments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada peserta yang lulus dari program ini'
            ], 422);
        }

        // Filter peserta yang belum memiliki sertifikat untuk program ini
        $eligibleEnrollments = $completedEnrollments->filter(function($enrollment) use ($programId) {
            // Cek apakah user sudah memiliki sertifikat untuk program ini
            $existingCertificate = Certificate::where('user_id', $enrollment->user_id)
                                            ->where('program_id', $programId)
                                            ->exists();
            
            return !$existingCertificate;
        });

        if ($eligibleEnrollments->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua peserta dari program ini sudah memiliki sertifikat'
            ], 422);
        }

        $createdCertificates = [];
        $skippedUsers = [];
        $errors = [];
        $incrementCounter = 0;

        foreach ($eligibleEnrollments as $enrollment) {
            try {
                // Double check untuk memastikan tidak ada duplikat saat proses berlangsung
                $existingCheck = Certificate::where('user_id', $enrollment->user_id)
                                           ->where('program_id', $programId)
                                           ->exists();

                if ($existingCheck) {
                    $skippedUsers[] = $enrollment->user->name . " (sudah memiliki sertifikat)";
                    Log::info("Skipping certificate creation for user {$enrollment->user->name} - already has certificate");
                    continue;
                }

                // Generate certificate number using the reference function format
                $certificateNumber = $this->generateCertificateNumber($issueDate, $incrementCounter);
                $incrementCounter++;

                // Create certificate
                $certificate = Certificate::create([
                    'certificate_number' => $certificateNumber,
                    'user_id' => $enrollment->user_id,
                    'program_id' => $programId,
                    'issue_date' => $issueDate,
                    'status' => $status,
                ]);

                // Attach competency units to the certificate
                $certificate->competencyUnits()->attach($competencyUnitIds);

                $createdCertificates[] = $certificate;

                Log::info("Certificate created for user {$enrollment->user->name} with ID {$certificate->id} and number {$certificateNumber}");

            } catch (\Exception $e) {
                $errors[] = "Gagal membuat sertifikat untuk {$enrollment->user->name}: " . $e->getMessage();
                Log::error("Error creating certificate for user {$enrollment->user_id}: " . $e->getMessage());
            }
        }

        $successCount = count($createdCertificates);
        $skippedCount = count($skippedUsers);
        $errorCount = count($errors);
        $totalProcessed = $completedEnrollments->count();

        // Prepare response message
        $message = "";
        if ($successCount > 0) {
            $message = "Berhasil menerbitkan {$successCount} sertifikat baru";
        }
        
        if ($skippedCount > 0) {
            $message .= ($successCount > 0 ? ", " : "") . "{$skippedCount} peserta dilewati (sudah memiliki sertifikat)";
        }
        
        if ($errorCount > 0) {
            $message .= ($successCount > 0 || $skippedCount > 0 ? ", " : "") . "{$errorCount} gagal diproses";
        }

        if (empty($message)) {
            $message = "Tidak ada sertifikat yang dibuat";
        }

        $responseData = [
            'created_count' => $successCount,
            'skipped_count' => $skippedCount,
            'error_count' => $errorCount,
            'total_processed' => $totalProcessed,
            'errors' => $errors,
            'skipped_users' => $skippedUsers
        ];

        if ($successCount > 0) {
            $responseData['certificates'] = collect($createdCertificates)->map(function($cert) { 
                return [
                    'id' => $cert->id,
                    'certificate_number' => $cert->certificate_number,
                    'user_name' => $cert->user->name ?? 'N/A'
                ];
            });
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => $message,
            'data' => $responseData
        ]);

    } catch (\Exception $e) {
        Log::error('Error in bulk certificate creation: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Generate certificate number with custom format
 * Based on the reference function provided
 */
private function generateCertificateNumber($issueDate, $increment = 0)
{
    $month = date('n', strtotime($issueDate));
    $year = date('Y', strtotime($issueDate));
    $romanMap = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    $romanMonth = $romanMap[$month - 1];

    // Hitung nomor urut berdasarkan jumlah sertifikat yang sudah ada
    $lastCertCount = Certificate::count() + 1 + $increment;
    $formattedCount = sprintf('%02d', $lastCertCount);

    $number = "{$formattedCount}/IT-DIGITAL/LPK-SID/{$romanMonth}/{$year}";

    // Pemeriksaan untuk menghindari duplikat nomor sertifikat
    $attemptCount = 0;
    $originalNumber = $number;
    
    while (Certificate::where('certificate_number', $number)->exists() && $attemptCount < 10) {
        $attemptCount++;
        $lastCertCount++;
        $formattedCount = sprintf('%02d', $lastCertCount);
        $number = "{$formattedCount}/IT-DIGITAL/LPK-SID/{$romanMonth}/{$year}";
    }

    // Jika masih duplikat setelah 10 percobaan, tambahkan unique identifier
    if (Certificate::where('certificate_number', $number)->exists()) {
        $number = "{$formattedCount}/IT-DIGITAL/LPK-SID/{$romanMonth}/{$year}-" . strtoupper(substr(uniqid(), -4));
    }

    return $number;
}
    
}