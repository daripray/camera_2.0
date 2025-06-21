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
        // Ambil daftar video
        $this->videos = collect(Storage::disk('public')->files('videos'))
            ->filter(fn($file) => str_ends_with($file, '.webm'))
            ->sortDesc()
            ->values();

        // Ambil isi log
        // if (Storage::exists('log.txt')) {
        //     $this->logs = explode("\n", Storage::get('log.txt'));
        // }
        
        $logPath =  storage_path('app/log.txt'); // atau storage/log.txt sesuai lokasi sebenarnya
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $this->logs = array_reverse($lines); // Terbaru di atas
        }
    }

    public function render()
    {
        return view('livewire.log-viewer')->layout('layouts.app');
    }
}

