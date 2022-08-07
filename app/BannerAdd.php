<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerAdd extends Model
{
    
    protected $fillable = [
        'image',
        'link',
        'position',
        'is_active',
        'column',
        'detail_page',
    ];

    public function banneradd_menu()
    {
        return $this->hasmany('App\BannerAddMenu', 'banneradd_id');
    }
}
