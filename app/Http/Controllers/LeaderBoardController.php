<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderBoardController extends Controller
{
    public function __invoke()
    {
        return view('leaderboard')
            ->with('users', User::orderBy('click_count', 'desc')->paginate('50'));
    }
}
