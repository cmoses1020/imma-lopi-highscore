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
        $this->userClicks = auth()->user()?->clicks->count() ?? 0;
        $this->totalClicks = Click::count();
        $this->rank = auth()->user()?->rankWithOrdinal ?? null;
    }

    public function click()
    {
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
