<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roll extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'value',
    ];

    protected $appends = ['bet_count', 'red_bet_count', 'black_bet_count', 'green_bet_count', 'win', 'lose', 'amount'];

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public function getBetCountAttribute()
    {
        return $this->bets()->count();
    }

    public function getRedBetCountAttribute()
    {
        return $this->bets()->red()->count();
    }

    public function getBlackBetCountAttribute()
    {
        return $this->bets()->black()->count();
    }

    public function getGreenBetCountAttribute()
    {
        return $this->bets()->green()->count();
    }

    public function getWinAttribute()
    {
        return $this->bets()->where('color', '=', $this->color)->count();
    }

    public function getLoseAttribute()
    {
        return $this->bets()->where('color', '!=', $this->color)->count();
    }

    public function getAmountAttribute()
    {
        return $this->bets()->sum('value');
    }

    public function scopeEnded($query)
    {
        return $query->whereNotNull('ended_at');
    }
}
