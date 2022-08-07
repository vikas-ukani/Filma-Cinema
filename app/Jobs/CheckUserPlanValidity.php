<?php

namespace App\Jobs;

use App\Mail\SendReminderEmail;
use App\PaypalSubscription;
use App\ReminderMail;
use App\User;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Mail;

class CheckUserPlanValidity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $all = PaypalSubscription::all();
        foreach ($all as $key => $value) {
            /*get before*/

            $cur_date = date('Y-m-d');
            $plan_end_date = $value->subscription_to;
            $datetime1 = new DateTime($cur_date);
            $datetime2 = new DateTime($plan_end_date);
            $interval = $datetime1->diff($datetime2);
            $interval2 = $datetime2->diff($datetime1);
            $beforedays = $interval->format('%a');

            $afterdays = $interval2->format('%a');
            $url = url('account/purchaseplan');
            if ($beforedays == 7 && $value->status == 1) {
                /*fire a mail*/

                $msg = 'Your subscription will expire in 7 days';
                $exsitRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->where('before_7day', 1)->first();
                if (!isset($exsitRow)) {
                    try {
                        Mail::to($value->user->email)->send(new SendReminderEmail($msg, $url));

                        if (Mail::failures()) {
                            // return failed mails
                            return new Error(Mail::failures());
                        } else {

                            $reminderRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->first();
                            if (isset($reminderRow) && $reminderRow != null) {
                                ReminderMail::where('id', $reminderRow->id)->update(['user_id' => $value->user->id, 'before_7day' => 1]);
                            } else {
                                ReminderMail::create(['subscription_id' => $value->id, 'user_id' => Auth::user()->id, 'before_7day' => 1, 'today' => null, 'after_7day' => null]);
                            }

                        }
                    } catch (\Swift_TransportException $e) {
                    }
                }
            }
            if ($afterdays == 7 && $value->status == 0) {
                /*fire a mail*/

                $msg = 'Your subscription is expiring today';
                $exsitRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->where('today', 1)->first();
                if (!isset($exsitRow)) {
                    try {
                        Mail::to($value->user->email)->send(new SendReminderEmail($msg, $url));

                        if (Mail::failures()) {
                            // return failed mails
                            return new Error(Mail::failures());
                        } else {
                            //dd($s);
                            $reminderRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->first();
                            if (isset($reminderRow) && $reminderRow != null) {
                                ReminderMail::where('id', $reminderRow->id)->update(['user_id' => $value->user->id, 'today' => 1]);
                            } else {
                                ReminderMail::create(['subscription_id' => $value->id, 'user_id' => Auth::user()->id, 'before_7day' => null, 'today' => 1, 'after_7day' => null]);
                            }

                        }
                    } catch (\Swift_TransportException $e) {
                    }
                }
            }
            if ($beforedays == 0 && $value->status == 1) {
                /*fire a mail*/

                $msg = 'Your Plan is expire.';
                $exsitRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->where('after_7day', 1)->first();
                if (!isset($exsitRow)) {
                    try {
                        Mail::to($value->user->email)->send(new SendReminderEmail($msg, $url));

                        if (Mail::failures()) {
                            // return failed mails
                            return new Error(Mail::failures());
                        } else {
                            //dd($s);
                            $reminderRow = ReminderMail::where('user_id', $value->user->id)->where('subscription_id', $value->id)->first();
                            if (isset($reminderRow) && $reminderRow != null) {
                                ReminderMail::where('id', $reminderRow->id)->update(['user_id' => $value->user->id, 'after_7day' => 1]);
                            } else {
                                ReminderMail::create(['subscription_id' => $value->id, 'user_id' => Auth::user()->id, 'before_7day' => null, 'today' => null, 'after_7day' => 1]);
                            }

                        }
                    } catch (\Swift_TransportException $e) {
                    }
                }

            }
        }
    }
}
