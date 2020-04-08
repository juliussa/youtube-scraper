<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'yt_id',
        'title',
        'published_at'
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
