<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatSetting extends Model
{

    protected $fillable = ['key', 'script', 'enable_messanger', 'mobile', 'text', 'color', 'size', 'header', 'enable_whatsapp', 'position'];

    public $timestamps = false;
}
