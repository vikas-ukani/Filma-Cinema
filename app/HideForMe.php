<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HideForMe extends Model
{
    use HasFactory;

    protected $table = 'hide_for_me';

    protected $casts = [
        'profile' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'type',
        'movie_id',
        'season_id',
        'profile'

    ];

    public function movie(){
        return $this->belongsTo('App\Movie','movie_id','id')->withDefault();
    }

    public function season(){
        return $this->belongsTo('App\Season','season_id','id')->withDefault();
    }
}
