<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Generate share URLs for a program
     */
    public function getShareUrls(Program $program)
    {
        $programUrl = route('programs.show', $program);
        $programTitle = $program->title;
        $programDescription = $program->description ? substr(strip_tags($program->description), 0, 150) . '...' : '';
        
        $shareUrls = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?' . http_build_query([
                'u' => $programUrl,
                'quote' => $programTitle
            ]),
            'twitter' => 'https://twitter.com/intent/tweet?' . http_build_query([
                'url' => $programUrl,
                'text' => $programTitle . ' - ' . $programDescription,
                'hashtags' => 'pelatihan,program,belajar'
            ]),
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?' . http_build_query([
                'url' => $programUrl
            ]),
            'whatsapp' => 'https://wa.me/?' . http_build_query([
                'text' => $programTitle . ' - ' . $programDescription . ' ' . $programUrl
            ]),
            'telegram' => 'https://t.me/share/url?' . http_build_query([
                'url' => $programUrl,
                'text' => $programTitle . ' - ' . $programDescription
            ]),
            'email' => 'mailto:?' . http_build_query([
                'subject' => 'Program Menarik: ' . $programTitle,
                'body' => "Hai!\n\nSaya ingin berbagi program menarik ini dengan Anda:\n\n" . 
                         $programTitle . "\n" . 
                         $programDescription . "\n\n" .
                         "Lihat detail lengkapnya di: " . $programUrl . "\n\n" .
                         "Terima kasih!"
            ])
        ];
        
        return response()->json([
            'urls' => $shareUrls,
            'program_url' => $programUrl,
            'title' => $programTitle,
            'description' => $programDescription
        ]);
    }

}