<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'name',
        'email',
        'comment',
        'blog_id',
        'user_id',

    ];

    public function blogs()
    {
        return $this->belongsTo('App\Blog')->withDefault();
    }
    public function subcomments()
    {
        return $this->hasmany('App\Subcomment');
    }

    public function users()
    {

        return $this->hasmany('App\User');
    }
}
