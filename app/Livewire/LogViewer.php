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
        // Ambil semua video .webm
        $this->videos = collect(Storage::disk('public')->files('videos'))
            ->filter(fn($file) => str_ends_with($file, '.webm'))
            ->map(function ($file) {
                return [
                    'path' => $file,
                    'name' => basename($file),
                ];
            })
            ->sortByDesc('name')
            ->values();

        // Ambil isi log dari file log.txt
        $logPath = storage_path('app/log.txt');
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $this->logs = array_reverse($lines); // terbaru di atas
        }
    }
    public function render()
    {
        return view('livewire.log-viewer')->layout('layouts.app');
    }
}

