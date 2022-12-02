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
}
