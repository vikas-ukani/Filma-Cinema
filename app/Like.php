<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'blog_id',
        'added',
    ];

    public function users()
    {
        return $this->belongsTo('App\User')->withDefault();
    }

    public function blog()
    {
        return $this->belongsTo('App\Blog')->withDefault();
    }

}
