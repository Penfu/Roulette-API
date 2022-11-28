<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $fillable = [
        'user_id',
        'color',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
