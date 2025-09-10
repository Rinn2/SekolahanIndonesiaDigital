<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Http\Requests\Admin\StoreGalleryCategoryRequest;
use App\Http\Requests\Admin\UpdateGalleryCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = GalleryCategory::withCount('galleries')->orderBy('name')->paginate(10);
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $categories->items(),
                'meta' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'per_page' => $categories->perPage(),
                    'total' => $categories->total(),
                    'from' => $categories->firstItem(),
                    'to' => $categories->lastItem()
                ]
            ]);
        }
        
        return view('admin.gallery-category.index', compact('categories'));
    }

    public function store(StoreGalleryCategoryRequest $request)
    {
        try {
            $category = GalleryCategory::create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateGalleryCategoryRequest $request, GalleryCategory $category)
    {
        try {
            $category->update($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(GalleryCategory $category)
    {
        try {
            // Cek apakah kategori masih digunakan
            if ($category->galleries()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki galeri'
                ], 422);
            }
            
            $category->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(GalleryCategory $category)
    {
        try {
            DB::beginTransaction();
            
            $oldStatus = $category->is_active;
            $newStatus = !$oldStatus;
            
            // Update kategori status
            $category->update(['is_active' => $newStatus]);
            
            // Jika kategori dinonaktifkan, nonaktifkan semua galeri dalam kategori tersebut
            if (!$newStatus) {
                $affectedGalleries = $category->galleries()->where('is_active', true)->update(['is_active' => false]);
            } else {
                $affectedGalleries = 0;
            }
            
            DB::commit();
            
            $freshCategory = $category->fresh();
            return response()->json([
                'success' => true,
                'message' => $affectedGalleries > 0 
                    ? "Status kategori berhasil diubah. {$affectedGalleries} galeri dalam kategori ini juga telah dinonaktifkan."
                    : 'Status kategori berhasil diubah',
                'data' => [
                    'is_active' => (bool) $freshCategory->is_active,
                    'category' => $freshCategory,
                    'affected_galleries' => $affectedGalleries
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(GalleryCategory $category)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori: ' . $e->getMessage()
            ], 500);
        }
    }
}
