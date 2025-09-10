<?php

namespace App\Services\Admin;

use App\Models\GalleryCategory;
use Illuminate\Support\Str;

class GalleryCategoryService
{
    public function store(array $validatedData): GalleryCategory
    {
        $validatedData['slug'] = Str::slug($validatedData['name']);
        return GalleryCategory::create($validatedData);
    }

    public function update(GalleryCategory $category, array $validatedData): bool
    {
        $validatedData['slug'] = Str::slug($validatedData['name']);
        return $category->update($validatedData);
    }

    public function destroy(GalleryCategory $category): ?bool
    {
        if ($category->galleries()->count() > 0) {
            // Mengembalikan null untuk menandakan kegagalan karena relasi
            return null;
        }
        return $category->delete();
    }
}