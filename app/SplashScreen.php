<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class SplashScreen extends Model
{
  

    protected $fillable = [
    	'image',
    	'logo_enable',
      	'logo'
    ];
}
