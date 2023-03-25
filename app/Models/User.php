<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\RankAndClickCountScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    protected $appends = [
        'rank_with_ordinal',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new RankAndClickCountScope);
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function getRankWithOrdinalAttribute()
    {
        return (new NumberFormatter('en_US', NumberFormatter::ORDINAL))
            ->format($this->user_rank);
    }
}
