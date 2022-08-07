<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuGenreShow extends Model
{
    protected $table = 'menu_genre_shows';

    protected $fillable = [
        'menu_id',
        'menu_section_id',
        'genre_id',
    ];

    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id', 'id')->withDefault();
    }

    public function menu_section()
    {
        return $this->belongsTo('App\MenuSection', 'menu_section_id', 'id')->withDefault();
    }
    public function genre()
    {
        return $this->belongsTo('App\Genre', 'genre_id', 'id')->withDefault();
    }
}
