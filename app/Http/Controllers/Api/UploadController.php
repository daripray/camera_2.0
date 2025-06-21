<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function ping(Request $request){
        $result = "pong";
        return $result;
    }
    public function read(Request $request){
        $result = true;
        return $result;
    }
    public function store(Request $request)
    {
        if (!$request->hasFile('video')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('video');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Invalid file'], 422);
        }

        if ($file->getSize() === 0) {
            return response()->json(['error' => 'Uploaded file is empty'], 422);
        }

        $filename = 'VID_' . now()->format('Ymd_His') . '.webm';
        $path = $file->storeAs('videos', $filename, 'public');

        // Ambil ukuran file setelah disimpan
        $filesize = Storage::disk('public')->size($path); // dalam bytes

        // Simpan log
        $datetime = now();
        Storage::append('log.txt', "Datetime:{$datetime}; Name:{$filename}; Size:{$filesize}");

        return response()->json([
            'message'  => 'Upload berhasil',
            'filename' => $filename,
            'filesize' => $filesize // ← ukuran file dalam bytes
        ]);
    }

}

