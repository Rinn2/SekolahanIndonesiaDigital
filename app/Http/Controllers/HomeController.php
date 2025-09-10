<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\User;
use App\Models\Gallery;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $programs = Program::active()
                          ->take(3)
                          ->orderBy('created_at', 'desc')
                          ->get();

        $stats = [
            'graduates' => 500,
            'programs' => Program::active()->count(),
            'instructors' => User::instructors()->count(),
            'satisfaction' => 95
        ];

        return view('home.index', compact('programs', 'stats'));
    }

    public function about()
    {
        return view('home.about');
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function galeri()
    {
        $galleries = Gallery::with('category')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category.name');

        $gallery_sections = [];

        foreach ($galleries as $categoryName => $categoryGalleries) {
            if ($categoryGalleries->count() > 0) {
                $categoryData = $categoryGalleries->first()->category;
                
                $gallery_sections[$categoryName] = [
                    'title' => $categoryData->name ?? $categoryName,
                    'description' => $categoryData->description ?? '',
                    'images' => $categoryGalleries->map(function ($gallery) {
                        return [
                            'id' => $gallery->id,
                            'url' => $this->getImageUrl($gallery),
                            'alt' => $gallery->title,
                            'title' => $gallery->title,
                            'description' => $gallery->description ?? ''
                        ];
                    })->toArray()
                ];
            }
        }

        return view('home.galeri', compact('gallery_sections'));
    }

    /**
     *  Untuk menangani URL gambar agar fleksibel meski nama project diganti
     */
    private function getImageUrl($gallery)
    {
        $imageUrl = $gallery->image_url;

        // Jika kosong → pakai placeholder
        if (empty($imageUrl)) {
            return $this->getPlaceholderImage();
        }

        // Jika sudah URL lengkap (http/https) → kembalikan langsung
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }

        // Bersihkan path dari slash awal jika ada
        $imageUrl = ltrim($imageUrl, '/');
        
        // Ambil hanya nama file (biar tidak tergantung path lama di DB)
        $filename = basename($imageUrl);

        // Daftar kemungkinan lokasi penyimpanan (urutan prioritas)
        $possiblePaths = [
            // Path dengan struktur folder yang mungkin
            "images/galeri/{$filename}",
            "storage/images/galeri/{$filename}",
            "storage/app/public/images/galeri/{$filename}",
            "storage/gallery/{$filename}",
            "uploads/gallery/{$filename}",
            "public/images/galeri/{$filename}",
            "public/storage/images/galeri/{$filename}",
            
            // Fallback ke nama file saja di berbagai folder
            "images/{$filename}",
            "storage/images/{$filename}",
            "uploads/{$filename}",
            
            // Path original (jika masih valid)
            $imageUrl,
        ];

        // Cek apakah file ada di public path
        foreach ($possiblePaths as $path) {
            $fullPath = public_path($path);
            if (file_exists($fullPath) && is_file($fullPath)) {
                return asset($path);
            }
        }

        // Cek di storage disk jika menggunakan Laravel Storage
        if (Storage::disk('public')->exists("images/galeri/{$filename}")) {
            return Storage::url("images/galeri/{$filename}");
        }

        // Cek storage dengan berbagai path
        $storagePaths = [
            "images/galeri/{$filename}",
            "gallery/{$filename}",
            "images/{$filename}",
            $filename
        ];

        foreach ($storagePaths as $storagePath) {
            if (Storage::disk('public')->exists($storagePath)) {
                return Storage::url($storagePath);
            }
        }

        // Jika semua gagal, coba debug log untuk troubleshooting
        \Log::warning("Image not found for gallery ID: {$gallery->id}, image_url: {$imageUrl}");

        // Return placeholder
        return $this->getPlaceholderImage();
    }
private function getPlaceholderImage()
    {
        $placeholders = [
            'images/placeholder.jpg',
            'images/placeholder.png', 
            'assets/images/placeholder.jpg',
            'assets/images/no-image.png'
        ];

        foreach ($placeholders as $placeholder) {
            if (file_exists(public_path($placeholder))) {
                return asset($placeholder);
            }
        }

        // Jika tidak ada placeholder, return base64 placeholder
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjY2NjIi8+CiAgPHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkdhbWJhciBUaWRhayBEaXRlbXVrYW48L3RleHQ+Cjwvc3ZnPg==';
    }


    /**
     * AJAX endpoint untuk mengambil data galeri
     */
    public function galleryData(Request $request)
    {
        $category = $request->get('category');
        
        $query = Gallery::where('is_active', true)->orderBy('created_at', 'desc');
        
        if ($category) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('name', $category);
            });
        }
        
        $galleries = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'url' => $this->getImageUrl($gallery),
                    'alt' => $gallery->title,
                    'title' => $gallery->title,
                    'description' => $gallery->description ?? '',
                    'category' => $gallery->category ? $gallery->category->name : null
                ];
            })
        ]);
    }
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255', 
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
