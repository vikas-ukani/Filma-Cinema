<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affilate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enable_affilate',
        'code_limit',
        'refer_amount',
        'about_system',
        'enable_purchase',
    ];
}
