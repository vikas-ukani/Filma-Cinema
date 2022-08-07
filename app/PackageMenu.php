<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageMenu extends Model
{

    protected $table = 'package_menu';

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    protected $fillable = [
        'menu_id',
        'package_id',
        'updated_at',
    ];
    public function menu()
    {
        return $this->belongsTo('App\Menu', 'menu_id', 'id')->withDefault();
    }
}
