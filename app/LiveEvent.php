<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveEvent extends Model
{

    /**
     * Convert the model instance to an array.
     *
     * @return array
     **/

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'poster',
        'type',
        'slug',
        'status',
        'iframeurl',
        'ready_url',
        'organized_by',
        'start_time',
        'end_time',

    ];

    public function menus()
    {
        return $this->hasMany('App\MenuVideo');
    }

}
