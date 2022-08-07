<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerAddMenu extends Model
{
    protected $table = 'banner_add_menus';

    public function banneraddm()
    {
        return $this->belongsTo('App\BannerAdd', 'banneradd_id', 'id')->withDefault();
    }
}
