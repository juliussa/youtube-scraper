<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['yt_id', 'title', 'published_at', 'channel_id'];

    protected $casts = [
        'datetime' => 'published_at'
    ];

    public function videoStats()
    {
        return $this->hasMany(VideoStat::class);
    }

    public function updateTags(array $tags)
    {
        $list = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['title' => $tagName]);
            $list[] = $tag->id;
        }
        $this->tags()->sync($list);
        
        return $this;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
