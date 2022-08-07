<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adsense extends Model
{
    protected $fillable = [
        'code',
        'status',
        'ishome',
        'isviewall',
        'issearch',
        'iswishlist',

    ];
}
