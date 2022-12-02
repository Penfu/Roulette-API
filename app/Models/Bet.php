<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $fillable = [
        'color',
        'value',
        'user_id',
        'roll_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roll()
    {
        return $this->belongsTo(Roll::class);
    }
}
