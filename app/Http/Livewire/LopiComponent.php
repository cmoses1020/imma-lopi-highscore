<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LopiComponent extends Component
{
    public function render()
    {
        return view('livewire.lopi-component')
            ->extends('layouts.app');
    }
}
