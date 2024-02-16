<?php

namespace App\Models;

use Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// import hasOne from Eloquent\
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'updated_at'
    ];

    protected $casts = [
        'avatar' => 'json',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['provider'];

    public function getProviderAttribute()
    {
        return OauthProvider::where('user_id', $this->id)->first()->name ?? null;
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function rolls()
    {
        return $this->hasMany(Roll::class);
    }

    private $colors = ['red', 'black', 'green'];

    public function getStatsAttribute()
    {
        $data = [];

        foreach ($this->colors as $color) {
            $data[$color] = [
                'bet' => [
                    'count' => $this->bets()->{$color}()->count(),
                    'amount' => $this->bets()->{$color}()->sum('amount'),
                ],
                'won' => [
                    'count' => $this->bets()->win()->{$color}()->count(),
                    'amount' => $this->bets()->win()->{$color}()->sum('amount'),
                ],
            ];
        }

        $data['bet'] = [
            'count' => $this->bets()->count(),
            'amount' => $this->bets()->sum('amount'),
        ];

        $data['won'] = [
            'count' => $this->bets()->win()->count(),
            'amount' => $this->bets()->win()->sum('amount'),
        ];

        return $data;
    }
}
