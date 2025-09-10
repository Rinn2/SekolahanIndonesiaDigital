<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CompetencyUnit;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// Menggunakan fasad Pdf dari library barryvdh/laravel-dompdf
use Barryvdh\DomPDF\Facade\Pdf;


class CertificateController extends Controller
{
    /**
     * menampilkan daftar sertifikat.
     */
    public function index()
    {
        $certificates = Certificate::with(['user', 'program', 'competencyUnits'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $programs = Program::all();
        $categories = CompetencyUnit::getCategories(); // Mendapatkan semua kategori
        
        return view('admin.certificates.index', compact('certificates', 'programs', 'categories'));
    }

    /**
     * mendapatkan kompetensi unit berdasarkan kategori.
     */
    public function getCompetencyUnits(Request $request)
    {
        try {
            $query = CompetencyUnit::select('id', 'unit_code', 'title', 'description', 'category')
                ->orderBy('unit_code');

            // Filter berdasarkan kategori jika ada
            if ($request->has('category') && !empty($request->category)) {
                $query->byCategory($request->category);
            }

            $units = $query->get();

            $formattedUnits = $units->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'unit_code' => $unit->unit_code,
                    'title' => $unit->title,
                    'description' => $unit->description,
                    'category' => $unit->category,
                    'display_text' => $unit->full_title
                ];
            });

            return response()->json([
                'success' => true,
                'units' => $formattedUnits
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get competency units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data unit kompetensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * mendapatkan semua kategori unit kompetensi.
     */
    public function getCategories()
    {
        try {
            $categories = CompetencyUnit::getCategories();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * mendapatkan unit kompetensi berdasarkan kategori untuk AJAX.
     */
    public function getUnitsByCategory(Request $request)
    {
        try {
            $category = $request->get('category');
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori harus dipilih'
                ], 400);
            }

            $units = CompetencyUnit::byCategory($category)
                ->select('id', 'unit_code', 'title', 'description')
                ->orderBy('unit_code')
                ->get();

            $formattedUnits = $units->map(function($unit) {
                return [
                    'id' => $unit->id,
                    'text' => $unit->full_title,
                    'unit_code' => $unit->unit_code,
                    'title' => $unit->title,
                    'description' => $unit->description
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedUnits
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get units by category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data unit kompetensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * menyimpan semua sertifikat secara massal.
     */
    public function storeBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:programs,id',
            'issue_date' => 'required|date',
            'status' => 'required|in:Kompeten,Belum Kompeten',
            'competency_unit_ids' => 'required|array|min:1',
            'competency_unit_ids.*' => 'exists:competency_units,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $program = Program::findOrFail($request->program_id);
            
            $participants = User::whereHas('enrollments', function($query) use ($request) {
                $query->where('program_id', $request->program_id)
                      ->where('enrollments.status', 'lulus');
            })->get();

            if ($participants->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada peserta dengan status "Lulus" yang ditemukan untuk program ini.'
                ], 404);
            }

            $certificatesCreated = 0;
            $competencyUnitIds = $request->competency_unit_ids;

            foreach ($participants as $participant) {
                $existingCertificate = Certificate::where('user_id', $participant->id)
                    ->where('program_id', $request->program_id)
                    ->exists();

                if ($existingCertificate) {
                    continue; // Skip jika sertifkat sudah ada
                }

                // Generate nomor sertifikat
                $certificateNumber = $this->generateCertificateNumber($request->issue_date, $certificatesCreated);

                // buat sertifkat
                $certificate = Certificate::create([
                    'user_id' => $participant->id,
                    'program_id' => $request->program_id,
                    'certificate_number' => $certificateNumber,
                    'issue_date' => $request->issue_date,
                    'status' => $request->status
                ]);

                // Lampirkan unit kompetensi
                $certificate->competencyUnits()->attach($competencyUnitIds);
                $certificatesCreated++;
            }

            DB::commit();

            if ($certificatesCreated === 0) {
                return response()->json([
                    'success' => true,
                    'message' => "Semua peserta lulus untuk program {$program->name} sudah memiliki sertifikat.",
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menerbitkan {$certificatesCreated} sertifikat baru untuk program {$program->name}.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating bulk certificates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server saat menerbitkan sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * menampilkan kompetensi pada sertfikat
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'program', 'competencyUnits']);
        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Generate sertifikat berdasarkan template
     */
    public function template(Certificate $certificate)
    {
        if (auth()->id() !== $certificate->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to certificate');
        }
        $certificate->load(['user', 'program', 'competencyUnits']);
        return view('certificates.template', compact('certificate'));
    }

    /**
     * Download Sertifikat 
     */
    public function download(Certificate $certificate)
    {
        if (auth()->id() !== $certificate->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to certificate');
        }
        
        $certificate->load(['user', 'program', 'competencyUnits']);
        
        $pdf = Pdf::loadView('certificates.template', compact('certificate'));
        
        $pdf->setPaper('A4', 'landscape');
        
     
        $pdf->setOptions([
            'isRemoteEnabled' => true, 
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Poppins',
        ]);

        $sanitizedCertNumber = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $certificate->certificate_number);
        $filename = 'sertifikat-' . $sanitizedCertNumber . '.pdf';
        
        return $pdf->download($filename);
    }


    public function stream(Certificate $certificate)
    {
        if (auth()->id() !== $certificate->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access to certificate');
        }
        
        $certificate->load(['user', 'program', 'competencyUnits']);
        
        $pdf = Pdf::loadView('certificates.template', compact('certificate'));
        $pdf->setPaper('A4', 'landscape');

        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Poppins',
        ]);
        
        $sanitizedCertNumber = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $certificate->certificate_number);
        $filename = 'sertifikat-' . $sanitizedCertNumber . '.pdf';
        
        return $pdf->stream($filename);
    }
   
    /**
     * Hapus sertifikat yang ditentukan.
     */
    public function destroy(Certificate $certificate)
    {
        try {
            $certificate->competencyUnits()->detach();
            $certificate->delete();
            return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Failed to delete certificate: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus sertifikat.'], 500);
        }
    }

    /**
     * menampilkan sertifikat peserta
     */
    public function participantCertificates()
    {
        $certificates = Certificate::where('user_id', auth()->id())
            ->with(['program', 'competencyUnits'])
            ->orderBy('issue_date', 'desc')
            ->get();
        return view('participant.certificates.index', compact('certificates'));
    }
}
