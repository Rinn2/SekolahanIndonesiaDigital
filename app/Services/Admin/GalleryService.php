<?php

namespace App\Services\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
    /**
     * Menyimpan gambar baru.
     */
    public function store(array $validatedData): Gallery
    {
        if (isset($validatedData['image'])) {
            $path = $validatedData['image']->store('public/gallery');
            $validatedData['image'] = Storage::url($path);
        }

        return Gallery::create($validatedData);
    }

    /**
     * Memperbarui gambar yang ada.
     */
    public function update(Gallery $gallery, array $validatedData): bool
    {
        if (isset($validatedData['image'])) {
            // Hapus gambar lama jika ada
            if ($gallery->image) {
                Storage::delete(str_replace('/storage', 'public', $gallery->image));
            }
            // Simpan gambar baru
            $path = $validatedData['image']->store('public/gallery');
            $validatedData['image'] = Storage::url($path);
        }

        return $gallery->update($validatedData);
    }

    /**
     * Menghapus gambar.
     */
    public function destroy(Gallery $gallery): bool
    {
        if ($gallery->image) {
            Storage::delete(str_replace('/storage', 'public', $gallery->image));
        }
        
        return $gallery->delete();
    }
}