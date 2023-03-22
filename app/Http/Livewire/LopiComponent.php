<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LopiComponent extends Component
{
    public $lopiCount;

    public function mount()
    {
        $this->lopiCount = auth()->user()->lopi_count ?? 0;
    }

    public function lopiCount($count)
    {
        auth()->user()->update([
            'lopi_count' => $count,
        ]);
    }

    public function rank()
    {
        return auth()->user()->placeInLeaderboard;
    }

    public function render()
    {
        return view('livewire.lopi-component')
            ->extends('layouts.app');
    }
}
