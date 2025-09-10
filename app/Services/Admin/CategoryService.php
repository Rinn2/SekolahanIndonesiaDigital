<?php

namespace App\Services\Admin;

use App\Models\GalleryCategory;
use Illuminate\Support\Facades\DB;
use Exception;

class CategoryService
{
    /**
     * Store a new gallery category
     *
     * @param array $data
     * @return GalleryCategory
     */
    public function store(array $data): GalleryCategory
    {
        return DB::transaction(function () use ($data) {
            return GalleryCategory::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);
        });
    }

    /**
     * Update an existing gallery category
     *
     * @param GalleryCategory $category
     * @param array $data
     * @return GalleryCategory
     */
    public function update(GalleryCategory $category, array $data): GalleryCategory
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? $category->description,
                'is_active' => $data['is_active'] ?? $category->is_active,
            ]);

            return $category->fresh();
        });
    }

    /**
     * Delete a gallery category
     *
     * @param GalleryCategory $category
     * @return bool|null
     */
    public function destroy(GalleryCategory $category): ?bool
    {
        return DB::transaction(function () use ($category) {
            // Check if category is being used by any galleries
            if ($category->galleries()->exists()) {
                return null; // Cannot delete - category is in use
            }

            return $category->delete();
        });
    }

    /**
     * Get all active categories
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories()
    {
        return GalleryCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all categories with galleries count
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithGalleryCount()
    {
        return GalleryCategory::withCount('galleries')
            ->orderBy('name')
            ->get();
    }
}