<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReminderMail extends Model
{
    protected $table = 'reminder_mails';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'subscription_id',
        'before_7day',
        'today',
        'after_7day',
    ];

}
