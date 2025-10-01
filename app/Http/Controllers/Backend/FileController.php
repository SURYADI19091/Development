<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'folder' => 'nullable|string|max:50'
        ]);
        
        $folder = $request->folder ?? 'uploads';
        $image = $request->file('image');
        
        // Generate unique filename
        $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
        
        // Store image
        $path = $image->storeAs($folder, $filename, 'public');
        
        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupload.',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'filename' => $filename
        ]);
    }
    
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'folder' => 'nullable|string|max:50'
        ]);
        
        $folder = $request->folder ?? 'documents';
        $document = $request->file('document');
        
        // Generate unique filename
        $originalName = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $document->getClientOriginalExtension();
        $filename = Str::slug($originalName) . '_' . Str::random(10) . '.' . $extension;
        
        // Store document
        $path = $document->storeAs($folder, $filename, 'public');
        
        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diupload.',
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
            'filename' => $filename,
            'original_name' => $document->getClientOriginalName(),
            'size' => $document->getSize()
        ]);
    }
    
    public function deleteFile($path)
    {
        $decodedPath = base64_decode($path);
        
        if (Storage::disk('public')->exists($decodedPath)) {
            Storage::disk('public')->delete($decodedPath);
            
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan.'
        ], 404);
    }
    
    public function getFileInfo(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);
        
        $path = $request->path;
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }
        
        $size = Storage::disk('public')->size($path);
        $lastModified = Storage::disk('public')->lastModified($path);
        $url = Storage::disk('public')->url($path);
        
        return response()->json([
            'success' => true,
            'data' => [
                'path' => $path,
                'url' => $url,
                'size' => $size,
                'size_human' => $this->formatBytes($size),
                'last_modified' => date('Y-m-d H:i:s', $lastModified),
                'exists' => true
            ]
        ]);
    }
    
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
        
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}