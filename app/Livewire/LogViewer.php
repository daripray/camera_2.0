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
        if (Storage::exists('log.txt')) {
            $this->logs = explode("\n", Storage::get('log.txt'));
        }
    }

    public function render()
    {
        return view('livewire.log-viewer')->layout('layouts.app');
    }
}

