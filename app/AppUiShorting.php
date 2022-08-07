<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUiShorting extends Model
{
    protected $fillable = [
        'is_active',
        'position',
    ];
}
