<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class HighScoreBoard extends Component
{
    public $users;

    public function getUsers()
    {
        return User::orderBy('click_count', 'desc')
            ->take(10)
            ->get()
            ->when(fn ($users) => $users->count() < 10,
                fn ($users) => $users->concat(
                    collect(range(1, 10 - $users->count()))
                        ->map(fn ($i) => tap(
                            User::make(['name' => '-']),
                            function ($user) use ($i, $users) {
                                $user->click_count = 0;
                                $user->user_rank = $i + $users->count();
                            })
                        )
                )
            )->toArray();
    }

    public function render()
    {
        return view('livewire.high-score-board');
    }
}
