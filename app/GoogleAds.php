<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAds extends Model
{

    protected $fillable = [
        'google_ad_client', 'google_ad_slot', 'google_ad_width', 'google_ad_height', 'google_ad_starttime', 'google_ad_endtime',
    ];
}
