<?php

namespace App\Livewire;

use Livewire\Component;

class FrontOffice extends Component
{
    public static $layout = null;

    public function render()
    {
        return view('livewire.front-office')->layout('layouts.app');
    }
}

