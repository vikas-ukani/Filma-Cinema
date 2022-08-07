<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multiplescreen extends Model
{
    protected $fillable = [
        'pkg_id', 'screen1', 'screen2', 'screen3', 'screen4', 'screen5', 'screen6', 'activescreen', 'user_id',
    ];
    public function users()
    {
        return $this->hasMany('App\User', 'id');
    }

    public function package()
    {
        return $this->belongsTo('App\Package', 'pkg_id', 'id')->withDefault();
    }
}
