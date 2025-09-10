<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query builder untuk Program
        $query = Program::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                // Cek kolom yang tersedia sebelum melakukan pencarian
                $availableColumns = Schema::getColumnListing('programs');
                
                if (in_array('name', $availableColumns)) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                }
                if (in_array('description', $availableColumns)) {
                    $q->orWhere('description', 'like', "%{$searchTerm}%");
                }
                if (in_array('instructor', $availableColumns)) {
                    $q->orWhere('instructor', 'like', "%{$searchTerm}%");
                }
            });
        }
        
        // Filter berdasarkan level
        if ($request->filled('level')) {
            $availableColumns = Schema::getColumnListing('programs');
            if (in_array('level', $availableColumns)) {
                $query->where('level', $request->get('level'));
            }
        }
        
        // Ambil data dengan pagination
        $programs = $query->orderBy('created_at', 'desc')->paginate(9);
        
        // Pastikan semua level tersedia untuk filter
        $levels = ['Pemula', 'Menengah', 'Lanjutan'];
        
        return view('programs.index', compact('programs', 'levels'));
    }
    
    public function show(Program $program)
    {
        // Debug: Log program yang diterima
        Log::info('Program received in show method:', [
            'id' => $program->id ?? 'null',
            'exists' => $program->exists,
            'attributes' => $program->getAttributes()
        ]);
        
        // Pastikan program ada dan memiliki data
        if (!$program || !$program->exists) {
            abort(404, 'Program tidak ditemukan');
        }
        
        // Ambil program terkait berdasarkan level yang sama, kecuali program saat ini
        $relatedPrograms = collect(); // Default empty collection
        
        try {
            $availableColumns = Schema::getColumnListing('programs');
            if (in_array('level', $availableColumns) && $program->level) {
                $relatedPrograms = Program::where('level', $program->level)
                    ->where('id', '!=', $program->id)
                    ->take(3)
                    ->get();
            } else {
                // Jika tidak ada kolom level, ambil program random
                $relatedPrograms = Program::where('id', '!=', $program->id)
                    ->inRandomOrder()
                    ->take(3)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Error getting related programs: ' . $e->getMessage());
            $relatedPrograms = collect(); // Empty collection jika error
        }
        
        return view('programs.show', compact('program', 'relatedPrograms'));
    }
    
    public function create()
    {
        return view('programs.create');
    }
    
    public function store(Request $request)
    {
        // Validasi dinamis berdasarkan kolom yang tersedia
        $availableColumns = Schema::getColumnListing('programs');
        $rules = [];
        
        if (in_array('title', $availableColumns)) {
            $rules['title'] = 'required|string|max:255';
        }
        if (in_array('description', $availableColumns)) {
            $rules['description'] = 'required|string';
        }
        if (in_array('level', $availableColumns)) {
            $rules['level'] = 'required|in:Pemula,Menengah,Lanjutan';
        }
        if (in_array('duration', $availableColumns)) {
            $rules['duration'] = 'required|string|max:100';
        }
        if (in_array('instructor', $availableColumns)) {
            $rules['instructor'] = 'nullable|string|max:255';
        }
        if (in_array('price', $availableColumns)) {
            $rules['price'] = 'nullable|numeric|min:0';
        }
        if (in_array('max_participants', $availableColumns)) {
            $rules['max_participants'] = 'nullable|integer|min:1';
        }
        if (in_array('start_date', $availableColumns)) {
            $rules['start_date'] = 'nullable|date';
        }
        if (in_array('end_date', $availableColumns)) {
            $rules['end_date'] = 'nullable|date|after:start_date';
        }
        
        $validated = $request->validate($rules);
        
        $program = Program::create($validated);
        
        return redirect()->route('programs.index')->with('success', 'Program berhasil ditambahkan!');
    }
    
    public function edit(Program $program)
    {
        // Pastikan program ada
        if (!$program || !$program->exists) {
            abort(404, 'Program tidak ditemukan');
        }
        
        return view('programs.edit', compact('program'));
    }
    
    public function update(Request $request, Program $program)
    {
        // Pastikan program ada
        if (!$program || !$program->exists) {
            abort(404, 'Program tidak ditemukan');
        }
        
        // Validasi dinamis berdasarkan kolom yang tersedia
        $availableColumns = Schema::getColumnListing('programs');
        $rules = [];
        
        if (in_array('title', $availableColumns)) {
            $rules['title'] = 'required|string|max:255';
        }
        if (in_array('description', $availableColumns)) {
            $rules['description'] = 'required|string';
        }
        if (in_array('level', $availableColumns)) {
            $rules['level'] = 'required|in:Pemula,Menengah,Lanjutan';
        }
        if (in_array('duration', $availableColumns)) {
            $rules['duration'] = 'required|string|max:100';
        }
        if (in_array('instructor', $availableColumns)) {
            $rules['instructor'] = 'nullable|string|max:255';
        }
        if (in_array('price', $availableColumns)) {
            $rules['price'] = 'nullable|numeric|min:0';
        }
        if (in_array('max_participants', $availableColumns)) {
            $rules['max_participants'] = 'nullable|integer|min:1';
        }
        if (in_array('start_date', $availableColumns)) {
            $rules['start_date'] = 'nullable|date';
        }
        if (in_array('end_date', $availableColumns)) {
            $rules['end_date'] = 'nullable|date|after:start_date';
        }
        
        $validated = $request->validate($rules);
        
        $program->update($validated);
        
        return redirect()->route('programs.show', $program)->with('success', 'Program berhasil diperbarui!');
    }
    
    public function destroy(Program $program)
    {
        // Pastikan program ada
        if (!$program || !$program->exists) {
            abort(404, 'Program tidak ditemukan');
        }
        
        $program->delete();
        
        return redirect()->route('programs.index')->with('success', 'Program berhasil dihapus!');
    }

    
}