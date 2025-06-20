<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class BackOffice extends Component
{
    public $videos = [];
    public $logContent = "";

    public function mount()
    {
        $this->videos = Storage::files('public/videos');
        $this->videos = array_map(fn($v) => basename($v), $this->videos);
        $this->logContent = Storage::exists('log.txt') ? Storage::get('log.txt') : 'Log kosong.';
    }

    public function render()
    {
        return view('livewire.back-office')->layout('layouts.app');
    }
}

