<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Stripe;
use Laravel\Cashier\Cashier;


class SendInvoiceMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $paypal_sub = null;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $auth = Auth::user();
        $user = Cashier::findBillable($auth->stripe_id);
        if($user){
            $invoices = $user->invoices();

            $invoice= $invoices[0];
        }
        

        // dd($invoice->lines->data[0]->plan->amount);

        return $this->view('user.invoice', compact('invoice', 'paypal_sub'))->subject('Send Invoice Mailable !');
    }
}
