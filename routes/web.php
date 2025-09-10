<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Peserta\DashboardController;
// Corrected Admin Controller paths
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryCategoryController as AdminGalleryCategoryController;
use App\Http\Controllers\Admin\GalleryCategoryController;

// Instructor Controllers
use App\Http\Controllers\Instruktur\InstructorController;
use App\Http\Controllers\Instruktur\GradeController;

// ============================
// Public Routes
// ============================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
Route::post('/galeri', [HomeController::class, 'galeriDetail'])->name('galeri.detail');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// ============================
// Authentication Routes
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ============================
// Email Verification Routes
// ============================
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ============================
// Authenticated User Routes
// ============================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/document/upload', [ProfileController::class, 'uploadDocument'])->name('profile.document.upload');
    Route::delete('/profile/document/delete', [ProfileController::class, 'deleteDocument'])->name('profile.document.delete');
    Route::get('/profile/document/download', [ProfileController::class, 'downloadDocument'])->name('profile.document.download');
    Route::get('/profile/document/view', [ProfileController::class, 'viewDocument'])->name('profile.document.view');
    Route::get('/profile/document/status', [ProfileController::class, 'getDocumentStatus'])->name('profile.document.status');

    Route::get('/email/verify-check', fn() => response()->json(['verified' => auth()->user()->hasVerifiedEmail()]))->name('verification.check');

    Route::middleware('verified')->group(function() {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'instruktur' => redirect()->route('instruktur.dashboard'),
                'peserta' => redirect()->route('peserta.dashboard'),
                default => abort(403, 'Unauthorized')
            };
        })->name('dashboard');

        // ============================
        // Peserta Routes
        // ============================
        Route::middleware('role:peserta')->group(function () {
            Route::get('/peserta/dashboard', [DashboardController::class, 'index'])->name('peserta.dashboard');
            Route::get('/programs/{program}/confirm', [EnrollmentController::class, 'confirm'])->name('enrollments.confirm');
            Route::post('/programs/{program}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');
            Route::get('/enrollments/{enrollment}/status', [EnrollmentController::class, 'status'])->name('enrollments.status');
            Route::get('/enrollments/{enrollment}/payment', [EnrollmentController::class, 'payment'])->name('enrollments.payment');
            Route::get('/enrollments/{enrollment}/payment/finish', [EnrollmentController::class, 'paymentFinish'])->name('enrollments.payment.finish');
            Route::post('/enrollments/{enrollment}/check-status', [EnrollmentController::class, 'manualCheckStatus'])->name('enrollments.check-status');
            Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
            Route::get('certificates/{certificate}/view', [CertificateController::class, 'template'])->name('certificate.view');
            Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
            Route::get('/participant/certificates/{certificate}/download', [CertificateController::class, 'downloadPdf'])->name('participant.certificates.download');
        });

        // ============================
        // Admin Routes
        // ============================
        Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // User Management (handled by AdminDashboardController)
            Route::prefix('users')->name('users.')->group(function () {
                Route::post('/', [AdminDashboardController::class, 'createUser'])->name('store');
                Route::get('/{id}', [AdminDashboardController::class, 'getUser'])->name('show');
                Route::put('/{id}', [AdminDashboardController::class, 'updateUser'])->name('update');
                Route::delete('/{id}', [AdminDashboardController::class, 'deleteUser'])->name('destroy');
            });

            // Program Management (handled by AdminDashboardController)
            Route::prefix('programs')->name('programs.')->group(function () {
                Route::post('/', [AdminDashboardController::class, 'createProgram'])->name('store');
                Route::get('/{id}', [AdminDashboardController::class, 'getProgram'])->name('show');
                Route::put('/{id}', [AdminDashboardController::class, 'updateProgram'])->name('update');
                Route::delete('/{id}', [AdminDashboardController::class, 'deleteProgram'])->name('destroy');
            });
            
            // Schedule Management (handled by AdminDashboardController)
            Route::prefix('schedules')->name('schedules.')->group(function () {
                Route::post('/', [AdminDashboardController::class, 'createSchedule'])->name('store');
                Route::get('/{id}', [AdminDashboardController::class, 'getSchedule'])->name('show');
                Route::put('/{id}', [AdminDashboardController::class, 'updateSchedule'])->name('update');
                Route::delete('/{id}', [AdminDashboardController::class, 'deleteSchedule'])->name('destroy');
            });

            // Enrollment Management (handled by AdminDashboardController)
            Route::prefix('enrollments')->name('enrollments.')->group(function () {
                Route::put('/{id}', [AdminDashboardController::class, 'updateEnrollment'])->name('update');
                Route::delete('/{id}', [AdminDashboardController::class, 'deleteEnrollment'])->name('destroy');
            });
            
            // Gallery & Category Management (Using standalone controllers)
            Route::resource('gallery-category', AdminGalleryCategoryController::class);
            Route::post('gallery-category/{category}/toggle-status', [AdminGalleryCategoryController::class, 'toggleStatus'])->name('gallery-category.toggle-status');
            Route::resource('gallery', GalleryController::class);
            Route::put('/gallery/{gallery}/status', [\App\Http\Controllers\Admin\GalleryController::class, 'toggleStatus'])->name('admin.gallery.toggleStatus');

            Route::get('/gallery-categories', [GalleryCategoryController::class, 'index'])->name('admin.gallery-categories.index');
        Route::post('/gallery-categories', [GalleryCategoryController::class, 'store'])->name('admin.gallery-categories.store');
        Route::get('/gallery-categories/{category}/edit', [GalleryCategoryController::class, 'edit'])->name('admin.gallery-categories.edit');
        Route::put('/gallery-categories/{category}', [GalleryCategoryController::class, 'update'])->name('admin.gallery-categories.update');
        Route::delete('/gallery-categories/{category}', [GalleryCategoryController::class, 'destroy'])->name('admin.gallery-categories.destroy');
        Route::put('/gallery-categories/{category}/toggle-status', [GalleryCategoryController::class, 'toggleStatus'])->name('admin.gallery-categories.toggle-status');
    Route::get('/admin/gallery-categories/{category}/edit', [GalleryCategoryController::class, 'edit']);

            // Certificate & Competency Unit Routes
            Route::post('/certificates/bulk', [AdminDashboardController::class, 'createCertificatesBulk'])->name('certificates.bulk.store');
            Route::get('/competency-units', [AdminDashboardController::class, 'getCompetencyUnits'])->name('competency-units.index');
            Route::post('/competency-units/by-category', [AdminDashboardController::class, 'getCompetencyUnitsByCategory'])->name('competency-units.by-category');
            Route::get('certificates/{certificate}/view', [CertificateController::class, 'template'])->name('certificates.view');
            Route::get('certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

            // Other Utility Routes
            Route::get('/schedule/form-data', [AdminDashboardController::class, 'getScheduleFormData'])->name('schedule.form-data');
            Route::get('/instructors', [AdminDashboardController::class, 'getInstructors'])->name('instructors');
            Route::get('/programs', [AdminDashboardController::class, 'getPrograms'])->name('programs');
        });

        // ============================
        // Instructor Routes
        // ============================
        Route::middleware('role:instruktur')->prefix('instruktur')->name('instruktur.')->group(function () {
            Route::get('/dashboard', [InstructorController::class, 'index'])->name('dashboard');
            Route::get('/schedule/{id}', [InstructorController::class, 'getSchedule'])->name('schedule.show');
            Route::put('/schedule/{id}', [InstructorController::class, 'updateSchedule'])->name('schedule.update');
            Route::get('/enrollment/{id}', [InstructorController::class, 'getEnrollment'])->name('enrollment.show');
            Route::match(['put', 'patch', 'post'], '/enrollment/{id}/update', [InstructorController::class, 'updateEnrollment']);
            Route::put('/enrollment/{id}/status', [InstructorController::class, 'updateEnrollmentStatus']);
            Route::get('/students/{scheduleId}', [InstructorController::class, 'getStudentProgress'])->name('students.progress');
            Route::get('/students/{scheduleId}/export', [InstructorController::class, 'exportStudents'])->name('students.export');
            Route::get('/student-progress/{enrollment_id}', [InstructorController::class, 'getStudentProgress']);
            Route::get('/export-students/{schedule_id}', [InstructorController::class, 'exportStudents']);
            Route::get('/enrollments/{enrollment}/grades', [GradeController::class, 'getGradesByEnrollment'])->name('grades.by_enrollment');
            Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
            Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
            Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
            Route::get('/enrollments/{enrollment}/progress', [GradeController::class, 'getStudentProgress'])->name('student.progress');
            Route::post('/grades/calculate-final', [GradeController::class, 'calculateFinalGrades'])->name('grades.calculate_final');
            Route::get('/students/ready-for-evaluation', [GradeController::class, 'getStudentsReadyForEvaluation'])->name('students.ready_evaluation');
        });
    });
});

// ============================
// Payment & Webhook Routes
// ============================
Route::post('/midtrans/notification', [EnrollmentController::class, 'handleNotification'])->name('midtrans.notification');
Route::post('/payment/webhook', [EnrollmentController::class, 'handleWebhook'])->name('payment.webhook');
Route::post('/enrollments/{enrollment}/payment/callback', [EnrollmentController::class, 'paymentCallback'])->name('enrollments.payment.callback');
Route::post('/enrollments/{enrollment}/payment/update', [EnrollmentController::class, 'updatePaymentStatus'])->name('enrollments.payment.update');
Route::post('/enrollments/{enrollment}/verify-payment', [EnrollmentController::class, 'verifyPaymentStatus'])->name('enrollments.payment.verify');

// ============================
// Error / Fallback
// ============================
Route::fallback(function () {
    logger('404 Page Not Found: ' . request()->url());
    return response()->view('errors.404', [
        'url' => request()->url(),
        'message' => 'Halaman yang Anda cari tidak ditemukan.'
    ], 404);
});
Route::get('/404', fn() => response()->view('errors.404', [], 404))->name('404');
