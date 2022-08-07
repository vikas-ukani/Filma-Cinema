<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    protected $table = 'menu_sections';

    protected $fillable = [
        'position',
    ];

    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id', 'id')->withDefault();
    }
}
