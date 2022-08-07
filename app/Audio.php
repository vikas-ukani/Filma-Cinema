<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{

    protected $fillable = [
        'title',
        'keyword',
        'thumbnail',
        'poster',
        'genre_id',
        'detail',
        'rating',
        'upload_audio',
        'maturity_rating',
        'featured',
        'type',
        'status',
        'is_protect',
        'password',
        'audiourl',
        'slug',
    ];
}
