<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthProvider extends Model
{
    protected $fillable = [
        'name',
        'provider_user_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
