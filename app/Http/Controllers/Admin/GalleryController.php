<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('category');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }
        
        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $galleries = $query->paginate(10);
        $categories = GalleryCategory::orderBy('name')->get();
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $galleries->items(),
                'meta' => [
                    'current_page' => $galleries->currentPage(),
                    'last_page' => $galleries->lastPage(),
                    'per_page' => $galleries->perPage(),
                    'total' => $galleries->total(),
                    'from' => $galleries->firstItem(),
                    'to' => $galleries->lastItem()
                ],
                'categories' => $categories
            ]);
        }
        
        return view('admin.gallery.index', compact('galleries', 'categories'));
    }

    public function store(StoreGalleryRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('gallery', 'public');
                $data['image'] = $imagePath;
            }
            
            // Set default values
            $data['is_active'] = $data['is_active'] ?? true;
            $data['sort_order'] = $data['sort_order'] ?? Gallery::max('sort_order') + 1;
            
            $gallery = Gallery::create($data);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil ditambahkan',
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                    Storage::disk('public')->delete($gallery->image);
                }
                
                $imagePath = $request->file('image')->store('gallery', 'public');
                $data['image'] = $imagePath;
            }
            
            $gallery->update($data);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil diperbarui',
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Gallery $gallery)
    {
        try {
            DB::beginTransaction();
            
            // Hapus gambar dari storage
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            
            $gallery->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Gallery $gallery)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Gallery $gallery)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data galeri: ' . $e->getMessage()
            ], 500);
        }
    }
    
public function toggleStatus(Gallery $gallery): \Illuminate\Http\JsonResponse
{
    try {
        // Toggle the boolean value
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();

        return response()->json([
            'message'   => 'Status galeri berhasil diperbarui.', // Message updated
            'is_active' => $gallery->is_active
        ]);
    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Gagal mengubah status galeri: ' . $e->getMessage()); // Log message updated
        return response()->json(['message' => 'Terjadi kesalahan pada server.'], 500);
    }
}
    
    public function activate(Gallery $gallery)
    {
        try {
            $gallery->update(['is_active' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil diaktifkan',
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deactivate(Gallery $gallery)
    {
        try {
            $gallery->update(['is_active' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil dinonaktifkan',
                'data' => $gallery->load('category')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getActiveGalleries()
    {
        try {
            $galleries = Gallery::active()
                ->with('category')
                ->ordered()
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $galleries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil galeri aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPublishedGalleries()
    {
        try {
            $galleries = Gallery::published()
                ->with('category')
                ->ordered()
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $galleries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil galeri yang dipublikasikan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:galleries,id',
                'action' => 'required|in:activate,deactivate,delete,move_category'
            ]);
            
            $ids = $request->ids;
            $action = $request->action;
            
            switch ($action) {
                case 'activate':
                    Gallery::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Galeri berhasil diaktifkan';
                    break;
                    
                case 'deactivate':
                    Gallery::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Galeri berhasil dinonaktifkan';
                    break;
                    
                case 'delete':
                    $galleries = Gallery::whereIn('id', $ids)->get();
                    foreach ($galleries as $gallery) {
                        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                            Storage::disk('public')->delete($gallery->image);
                        }
                    }
                    Gallery::whereIn('id', $ids)->delete();
                    $message = 'Galeri berhasil dihapus';
                    break;
                    
                case 'move_category':
                    $request->validate(['category_id' => 'required|exists:gallery_categories,id']);
                    Gallery::whereIn('id', $ids)->update(['category_id' => $request->category_id]);
                    $message = 'Galeri berhasil dipindahkan ke kategori baru';
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan operasi bulk: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|exists:galleries,id',
                'items.*.sort_order' => 'required|integer|min:0'
            ]);
            
            foreach ($request->items as $item) {
                Gallery::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Urutan galeri berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function duplicate(Gallery $gallery)
    {
        try {
            DB::beginTransaction();
            
            $newGallery = $gallery->replicate();
            $newGallery->title = $gallery->title . ' (Copy)';
            $newGallery->sort_order = Gallery::max('sort_order') + 1;
            $newGallery->save();
            
            // Duplicate image if exists
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                $extension = pathinfo($gallery->image, PATHINFO_EXTENSION);
                $newImagePath = 'gallery/' . uniqid() . '.' . $extension;
                Storage::disk('public')->copy($gallery->image, $newImagePath);
                $newGallery->update(['image' => $newImagePath]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil diduplikasi',
                'data' => $newGallery->load('category')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menduplikasi galeri: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $query = Gallery::with('category');
            
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }
            
            $galleries = $query->get();
            
            $filename = 'galleries_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($galleries) {
                $file = fopen('php://output', 'w');
                
                // Header
                fputcsv($file, ['ID', 'Judul', 'Deskripsi', 'Kategori', 'Status', 'Urutan', 'Gambar', 'Tanggal Dibuat']);
                
                // Data
                foreach ($galleries as $gallery) {
                    fputcsv($file, [
                        $gallery->id,
                        $gallery->title,
                        $gallery->description,
                        $gallery->category ? $gallery->category->name : '-',
                        $gallery->is_active ? 'Aktif' : 'Nonaktif',
                        $gallery->sort_order,
                        $gallery->image,
                        $gallery->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengekspor data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            $stats = [
                'total' => Gallery::count(),
                'active' => Gallery::where('is_active', true)->count(),
                'inactive' => Gallery::where('is_active', false)->count(),
                'with_images' => Gallery::whereNotNull('image')->count(),
                'without_images' => Gallery::whereNull('image')->count(),
                'by_category' => Gallery::with('category')
                    ->select('category_id', DB::raw('count(*) as total'))
                    ->groupBy('category_id')
                    ->get()
                    ->mapWithKeys(function($item) {
                        return [$item->category ? $item->category->name : 'Tanpa Kategori' => $item->total];
                    }),
                'recent' => Gallery::with('category')
                    ->latest()
                    ->take(5)
                    ->get()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}
