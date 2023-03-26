<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class HighScoreBoard extends Component
{
    public $users;

    public function poll()
    {
        $this->users = User::orderBy('user_rank')
            ->select('id', 'name', 'user_rank', 'click_count')
            ->where('user_rank', '<=', 10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'user_rank' => $user->user_rank,
                    'rank_with_ordinal' => $user->rankWithOrdinal,
                    'click_count' => $user->click_count,
                ];
            });
    }

    public function render()
    {
        $this->poll();

        return view('livewire.high-score-board');
    }
}
