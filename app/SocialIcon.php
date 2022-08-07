<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialIcon extends Model
{
    protected $fillable = [
        'url1', 'url2', 'url3',
    ];
}
