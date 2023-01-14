<?php

namespace App\Models;

use Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function rolls()
    {
        return $this->hasMany(Roll::class);
    }

    public function stats()
    {
        return [
            'bets' => $this->bets()->count(),
            'bets_on_red' => $this->bets()->where('color', 'red')->count(),
            'bets_on_black' => $this->bets()->where('color', 'black')->count(),
            'bets_on_green' => $this->bets()->where('color', 'green')->count(),

            'wins' => $this->winnedBets()->count(),
            'red_wins' => $this->winnedBets()->where('color', 'red')->count(),
            'black_wins' => $this->winnedBets()->where('color', 'black')->count(),
            'green_wins' => $this->winnedBets()->where('color', 'green')->count(),

            'total_bet' => $this->bets()->sum('value'),
            'total_winnings' => $this->winnedBets()->sum('value'),
        ];
    }

    public function winnedBets()
    {
        return $this->bets()->whereHas('roll', function ($query) {
            $query->where('color', $this->bets()->first()?->color);
        });
    }
}
