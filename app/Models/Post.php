<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Post extends Model
{
    use  Notifiable, HasApiTokens;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
