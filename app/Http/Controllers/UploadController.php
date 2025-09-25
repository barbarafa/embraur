<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function ckeditor(Request $r)
    {
        // CKEditor 5 manda o arquivo em "upload" (ckfinder/simpleUpload) ou "file"
        $file = $r->file('upload') ?? $r->file('file');
        abort_unless($file && $file->isValid(), 400, 'Arquivo inválido');

        // Pasta por tipo (só pra organizar)
        $mime = strtolower($file->getMimeType());
        $isImage = str_starts_with($mime, 'image/');
        $isVideo = str_starts_with($mime, 'video/');

        $dir = $isImage ? 'ckeditor/images' : ($isVideo ? 'ckeditor/videos' : 'ckeditor/files');

        // Salva no disco public
        $path = $file->store($dir, 'public');
        $url  = Storage::disk('public')->url($path);

        // Adapter do CKEditor 5 aceita esse formato:
        return response()->json([
            'uploaded' => true,
            'url'      => $url,
        ]);
    }
}
