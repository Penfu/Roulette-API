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

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    // Eloquent Scope to take only ended roll
    public function scopeEnded($query)
    {
        return $query->whereNotNull('ended_at');
    }
}
