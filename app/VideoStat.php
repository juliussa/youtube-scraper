<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoStat extends Model
{
    protected $fillable = [
        'previews',
        'video_id'
    ];
}
