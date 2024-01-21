<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'amount',
        'user_id',
        'roll_id',
    ];

    protected $appends = ['is_win'];

    protected $hidden = ['roll'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roll()
    {
        return $this->belongsTo(Roll::class);
    }

    public function getIsWinAttribute()
    {
        return $this->color === $this->roll->color;
    }

    public function ScopeRed($query)
    {
        return $query->where('color', 'red');
    }

    public function ScopeBlack($query)
    {
        return $query->where('color', 'black');
    }

    public function ScopeGreen($query)
    {
        return $query->where('color', 'green');
    }

    public function scopeWin($query)
    {
        return $query->whereHas('roll', function ($subQuery) {
            $subQuery->whereColumn('bets.color', 'rolls.color');
        });
    }
}
