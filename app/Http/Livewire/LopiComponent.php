<?php

namespace App\Http\Livewire;

use App\Models\Click;
use Livewire\Component;

class LopiComponent extends Component
{
    public $lopiCount;

    public function mount()
    {
        $this->lopiCount = auth()->user()?->clicks->count() ?? 0;
    }

    public function getRankAndTotalClicks()
    {
        return [
            'rank' => auth()->user()->placeInLeaderboard ?? null,
            'total_clicks' => Click::count(),
        ];
    }

    public function click()
    {
        Click::create([
            'user_id' => auth()->user()->id ?? null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.lopi-component')
            ->extends('layouts.app');
    }
}
