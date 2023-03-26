<?php

namespace App\Http\Livewire;

use App\Models\Click;
use Livewire\Component;

class LopiComponent extends Component
{
    public $userClicks = 0;

    public $totalClicks = 0;

    public $rank = null;

    public function poll()
    {
        if (auth()->check()) {
            $this->userClicks = auth()->user()->clicks->count();
            $this->rank = auth()->user()->rankWithOrdinal;
        } else {
            $this->userClicks = session('clicks');
        }
        $this->totalClicks = Click::count();
    }

    public function click()
    {
        if (auth()->guest()) {
            session(['clicks' => $this->userClicks + 1]);
        }

        Click::create([
            'user_id' => auth()->user()?->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        $this->poll();
    }

    public function render()
    {
        return view('livewire.lopi-component')
            ->extends('layouts.app');
    }
}
