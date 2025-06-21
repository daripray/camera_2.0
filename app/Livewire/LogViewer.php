<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class LogViewer extends Component
{
    public $videos = [];
    public $logs = [];

    public function mount()
    {
        // Ambil log lines
        $logPath = storage_path('app/log.txt');
        $logLines = [];
        if (file_exists($logPath)) {
            $logLines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        // Buat mapping nama file ke detail log
        $logMap = collect($logLines)
            ->map(function ($line) {
                // Cocokkan format: datetime:...; Name:...; Size:...
                if (preg_match('/Datetime:(.+?);\s*Name:(.+?\.webm);\s*Size:(\d+)/', $line, $match)) {
                    return [
                        'datetime' => $match[1],
                        'filename' => $match[2],
                        'filesize' => (int) $match[3],
                        'raw' => $line,
                    ];
                }
                return null;
            })
            ->filter()
            ->keyBy('filename'); // index berdasarkan nama file

        // Ambil semua video .webm
        $this->videos = collect(Storage::disk('public')->files('videos'))
            ->filter(fn($file) => str_ends_with($file, '.webm'))
            ->map(function ($file) use ($logMap) {
                $filename = basename($file);
                $log = $logMap->get($filename); // gunakan ->get() agar aman

                return [
                    'path' => $file,
                    'name' => $filename,
                    'log_detail' => $log, // bisa null jika tidak ditemukan
                ];
            })
            ->sortByDesc(fn($video) => $video['name']) // urut dari nama file terbaru
            ->values();

            // dd([$logLines,$logMap, $this->videos]);

        // Simpan log mentah juga kalau dibutuhkan di bawah
        $this->logs = array_reverse($logLines); // terbaru di atas
    }

    public function render()
    {
        return view('livewire.log-viewer')->layout('layouts.app');
    }
}

