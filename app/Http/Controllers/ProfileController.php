<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
       
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = \App\Models\User::with('documents')->find($user->id);
        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }
       
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
       
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            // PERBAIKAN: Conditional validation untuk NIK
            $nikRules = ['nullable', 'string'];
            
            // Hanya tambahkan validasi format dan unique jika NIK diisi
            if ($request->filled('nik')) {
                $nikRules[] = 'size:16';
                $nikRules[] = 'regex:/^[0-9]{16}$/';
                
                // Hanya validasi unique jika NIK berbeda dari yang sudah ada
                if ($request->nik !== $user->nik) {
                    $nikRules[] = Rule::unique('users', 'nik')->ignore($user->id);
                }
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'password' => 'nullable|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'birth_date' => 'nullable|date|before:today',
                'pekerjaan' => 'nullable|string|in:Belum/Tidak Bekerja,Mengurus Rumah Tangga,Pelajar/Mahasiswa,Pensiunan,Pegawai Negeri Sipil,Industri,Kontruksi,Transportasi,Karyawan Swasta,Karyawan BUMN,Karyawan BUMD,Karyawan Honorer,Dosen,Guru,Arsitek,Akuntan,Pialang,Wiraswasta,Lainnya',
                'gender' => 'nullable|in:L,P',
                'education' => 'nullable|string|in:SD,SMP,SMA/SMK,D3,S1,S2,S3',
                'programstudi' => 'nullable|string|max:255',
                'nik' => $nikRules,
            ], [
                'name.required' => 'Nama wajib diisi.',
                'nik.regex' => 'Format NIK tidak valid. NIK harus 16 digit angka.',
                'nik.size' => 'NIK harus tepat 16 digit.',
                'nik.unique' => 'NIK sudah digunakan oleh pengguna lain.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
                'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
                'birth_date.date' => 'Format tanggal lahir tidak valid.',
                'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
                'gender.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
                'education.in' => 'Pendidikan harus salah satu dari: SD, SMP, SMA/SMK, D3, S1, S2, S3.',
            ]);

            DB::transaction(function () use ($user, $validated) {
                $user->name = $validated['name'];
                $user->email = $validated['email'];
                $user->phone = $validated['phone'] ?? null;
                $user->address = $validated['address'] ?? null;
                $user->birth_date = $validated['birth_date'] ?? null;
                $user->gender = $validated['gender'] ?? null;
                $user->education = $validated['education'] ?? null;
                $user->programstudi = $validated['programstudi'] ?? null;
                $user->nik = $validated['nik'] ?? null;
                $user->pekerjaan = $validated['pekerjaan'] ?? null;

                if (!empty($validated['password'])) {
                    $user->password = Hash::make($validated['password']);
                }
               
                $result = $user->save();
               
                if (!$result) {
                    throw new Exception('Gagal menyimpan data user.');
                }
            });

            return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
           
        } catch (Exception $e) {
            Log::error('Error updating profile:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null,
                'validated_data' => $validated ?? null,
            ]);
           
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    public function uploadDocument(Request $request)
    {
        $user = Auth::user();
       
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $request->validate([
                'document_type' => 'required|in:pasfoto,ktp,ijazah_terakhir',
                'document_file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048', 
            ], [
                'document_type.required' => 'Jenis dokumen wajib dipilih.',
                'document_type.in' => 'Jenis dokumen tidak valid.',
                'document_file.required' => 'File dokumen wajib dipilih.',
                'document_file.file' => 'File tidak valid.',
                'document_file.mimes' => 'File harus berformat: jpeg, jpg, png, atau pdf.',
                'document_file.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
            ]);

            $documentType = $request->document_type;
            $file = $request->file('document_file');

            DB::transaction(function () use ($user, $documentType, $file) {
                $userDocument = $user->getOrCreateDocuments();

                $oldFilePath = $userDocument->{$documentType};
                if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }

                $fileName = $documentType . '_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('documents/' . $documentType, $fileName, 'public');

                // Menentukan nama kolom `_uploaded_at` yang benar
                $uploadedAtColumn = ($documentType === 'ijazah_terakhir')
                                    ? 'ijazah_uploaded_at'
                                    : $documentType . '_uploaded_at';

                $userDocument->{$documentType} = $filePath;
                $userDocument->{$uploadedAtColumn} = now();
               
                $userDocument->save();

                Log::info('Document uploaded successfully:', [
                    'user_id' => $user->id,
                    'document_type' => $documentType,
                    'file_path' => $filePath,
                ]);
            });

            $documentNames = [
                'pasfoto' => 'Pasfoto',
                'ktp' => 'KTP',
                'ijazah_terakhir' => 'Ijazah Terakhir'
            ];

            return redirect()->route('profile.show')
                ->with('success', $documentNames[$documentType] . ' berhasil diupload.');

        } catch (Exception $e) {
            Log::error('Error uploading document:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null,
                'document_type' => $request->document_type ?? null,
            ]);
           
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function deleteDocument(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'document_type' => ['required', Rule::in(['pasfoto', 'ktp', 'ijazah_terakhir'])]
        ]);
       
        $documentType = $validated['document_type'];
       
        if (!$user->documents || !$user->documents->{$documentType}) {
            return back()->with('error', 'Dokumen tidak ditemukan untuk dihapus.');
        }
       
        try {
            DB::transaction(function () use ($user, $documentType) {
                $filePath = $user->documents->{$documentType};
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }

                // Menentukan nama kolom `_uploaded_at` yang benar untuk di-reset
                $uploadedAtColumn = ($documentType === 'ijazah_terakhir')
                                    ? 'ijazah_uploaded_at'
                                    : $documentType . '_uploaded_at';

                // Update database dengan nama kolom yang sudah diperbaiki
                $user->documents->update([
                    $documentType => null,
                    $uploadedAtColumn => null
                ]);
            });

            return back()->with('success', 'Dokumen berhasil dihapus.');

        } catch (Exception $e) {
            Log::error('Error deleting document', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'document_type' => $documentType
            ]);
            return back()->with('error', 'Gagal menghapus dokumen.');
        }
    }

    public function viewDocument(Request $request)
    {
        $user = auth()->user();
        $documentType = $request->get('document_type');

        $allowedTypes = ['pasfoto', 'ktp', 'ijazah_terakhir'];
        if (!in_array($documentType, $allowedTypes)) {
            abort(404, 'Jenis dokumen tidak valid.');
        }
       
        if (!$user->documents || !$user->documents->{$documentType}) {
            abort(404, 'Dokumen tidak ditemukan.');
        }
       
        $filePath = $user->documents->{$documentType};

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }
       
        return response()->file(storage_path('app/public/' . $filePath));
    }
   

}