<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class HighScoreBoard extends Component
{
    use WithPagination;

    public $users;

    public $maxRank = 10;

    public $paginate = false;

    public function mount($maxRank, $paginate = false)
    {
        $this->paginate = $paginate;
        $this->maxRank = $maxRank;
    }

    public function poll()
    {
        $things = function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'user_rank' => $user->user_rank,
                'rank_with_ordinal' => $user->rankWithOrdinal,
                'click_count' => $user->click_count,
            ];
        };

        $this->users = User::orderBy('user_rank')
            ->select('id', 'name', 'user_rank', 'click_count')
            ->where('user_rank', '<=', $this->maxRank)
            ->get()
            ->map($things);
    }

    public function render()
    {
        $this->poll();

        return view('livewire.high-score-board');
    }
}
