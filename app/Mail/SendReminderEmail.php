<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReminderEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $msg;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg,$url)
    {
        //
         $this->msg = $msg;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('admin.email.reminder')->subject('Plan Expiry Reminder !');
    }
}
