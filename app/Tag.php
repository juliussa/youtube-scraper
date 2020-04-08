<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['title'];


    public function videos()
    {
        return $this->belongsToMany(Video::class);
    }
}
