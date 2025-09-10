<?php
namespace App\Http\Requests\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->fill($request->validated());
           $rules = [
    'name' => 'required|string|max:255|min:2',
    'email' => 'required|string|email|max:255|unique:users,email,' . $id,
    'role' => 'required|string|in:admin,instruktur,peserta',
    'nik' => 'nullable|digits:16|unique:users,nik,' . $id,
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

}