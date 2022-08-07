<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogMenu extends Model
{
    protected $table = 'blog_menu';

    public function blogs()
    {
        return $this->belongsTo('App\Blog', 'blog_id', 'id')->withDefault();
    }

}
