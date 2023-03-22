<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use NumberFormatter;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lopi_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPlaceInLeaderBoardAttribute()
    {
        $rank = DB::table(function ($query) {
            $query->select('id', 'lopi_count', DB::raw('RANK() OVER (ORDER BY lopi_count DESC, id ASC) as user_rank'))
                ->from('users');
        })
            ->select('user_rank')
            ->where('id', $this->id)
            ->first()
            ->user_rank;

        $nf = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

        return $nf->format($rank);
    }
}
