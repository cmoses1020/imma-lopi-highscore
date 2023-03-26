<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class HighScoreBoard extends Component
{
    public function getUsers()
    {
        return User::orderBy('user_rank')
            ->where('user_rank', '<=', 10)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.high-score-board');
    }
}
