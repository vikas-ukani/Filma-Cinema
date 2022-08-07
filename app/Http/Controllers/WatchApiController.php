<?php

namespace App\Http\Controllers;

use App\AppConfig;
use App\Episode;
use App\Movie;
use App\Package;
use App\Season;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Stripe\Customer;

class WatchApiController extends Controller
{
    public function __construct()
    {
        $this->button = AppConfig::first();
    }
    public function watch_trailer($user, $code, $id)
    {
        $data = DB::table('oauth_access_tokens')
            ->where('user_id', $user)
            ->where('revoked', 0)
            ->where('id', $code)->get();
        if (isset($data) && count($data) > 0) {
            $movie = Movie::find($id);
            return view('watch', compact('movie'));
        } else {
            abort(404);
        }
    }

    public function watchtv_trailer($user, $code, $id)
    {
        $data = DB::table('oauth_access_tokens')
            ->where('user_id', $user)
            ->where('revoked', 0)
            ->where('id', $code)->get();
        if (isset($data) && count($data) > 0) {

            $season = Season::find($id);
            if (isset($season->trailer_url) && $season->trailer_url != null) {

                return view('watchtv', compact('season'));
            }

        } else {
            abort(404);
        }
    }

    public function watch_tv($user, $code, $id)
    {
        $data = DB::table('oauth_access_tokens')
            ->where('user_id', $user)
            ->where('revoked', 0)
            ->where('id', $code)->get();
        $user = $user;
        $auth = User::find($user);
        $remove_ads = 0;
        if (isset($data) && count($data) > 0) {
            $season = Season::find($id);
            if (isset($season->episodes[0]) && $season->episodes[0]->video_link->iframeurl != null) {
                $link = $season->episodes[0]->video_link->iframeurl;
                return view('iframe', compact('season', 'link'));
            } else {
                if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
                    $subscribed = 1;
                    $remove_ads = 1;
                } else {
                    if ($auth->stripe_id != null) {
                        $customer = Customer::retrieve($auth->stripe_id);
                    }
                    $paypal = $auth
                        ->paypal_subscriptions
                        ->sortBy('created_at');
                    $plans = Package::all();
                    $current_date = Carbon::now()->toDateString();
                    if (isset($customer)) {

                        $alldata = $auth->subscriptions;
                        $data = $alldata->last();
                    }
                    if (isset($paypal) && $paypal != null && count($paypal) > 0) {
                        $last = $paypal->last();

                    }
                    $stripedate = isset($data) ? $data->created_at : null;
                    $paydate = isset($last) ? $last->created_at : null;
                    if ($stripedate > $paydate) {
                        if ($user->subscribed($data->name)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::where($data->name)->first();
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    } elseif ($stripedate < $paydate) {
                        if (date($current_date) <= date($last->subscription_to)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::find($last->package_id);
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    }
                }
                return view('watchTvShow', compact('season', 'user', 'remove_ads'));
            }

        } else {
            abort(404);
        }
    }

    public function watch_movie($user, $code, $id)
    {
        $udata = DB::table('oauth_access_tokens')
            ->where('user_id', $user)
            ->where('revoked', 0)
            ->where('id', $code)->get();
        $user = $user;
        $auth = User::find($user);
        $remove_ads = 0;
        if (isset($udata) && count($udata) > 0) {
            $movie = Movie::findorfail($id);
            if ($movie->video_link->iframeurl != null) {
                $link = $movie->video_link->iframeurl;
                return view('iframe', compact('movie', 'link'));
            } else {
                if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
                    $subscribed = 1;
                    $remove_ads = 1;
                } else {
                    
                    if ($auth->stripe_id != null) {
                        $customer = Customer::retrieve($auth->stripe_id);
                    }
                    $paypal = $auth
                        ->paypal_subscriptions
                        ->sortBy('created_at');
                    $plans = Package::all();
                    $current_date = Carbon::now()->toDateString();
                    if (isset($customer)) {
                       
                        $alldata = $auth->subscriptions;
                        $data = $alldata->last();
                    }
                    if (isset($paypal) && $paypal != null && count($paypal) > 0) {
                        $last = $paypal->last();

                    }
                    // dd($data->created_at);
                    $stripedate = isset($data) && $data != null ? $data->created_at : null;
                    $paydate = isset($last) && $last != null ? $last->created_at : null;
                    if ($stripedate > $paydate) {
                        if ($user->subscribed($data->name)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::where($data->name)->first();
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    } elseif ($stripedate < $paydate) {
                        if (date($current_date) <= date($last->subscription_to)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::find($last->package_id);
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    }
                }
                return view('watchMovie', compact('movie', 'user', 'remove_ads'));
            }
        }
    }

    public function watch_episode($user, $code, $id)
    {
        $data = DB::table('oauth_access_tokens')
            ->where('user_id', $user)
            ->where('revoked', 0)
            ->where('id', $code)->get();
        $user = $user;
        $remove_ads = 0;
        $auth = User::find($user);
        if (isset($data) && count($data) > 0) {
            $episode = Episode::find($id);
            $season = Season::find($episode->seasons_id);
            if ($episode->video_link->iframeurl != null) {
                $link = $episode->video_link->iframeurl;
                return view('iframe', compact('season', 'link'));
            } else {
                if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
                    $subscribed = 1;
                    $remove_ads = 1;
                } else {
                    if ($auth->stripe_id != null) {
                        $customer = Customer::retrieve($auth->stripe_id);
                    }
                    $paypal = $auth
                        ->paypal_subscriptions
                        ->sortBy('created_at');
                    $plans = Package::all();
                    $current_date = Carbon::now()->toDateString();
                    if (isset($customer)) {

                        $alldata = $auth->subscriptions;
                        $data = $alldata->last();
                    }
                    if (isset($paypal) && $paypal != null && count($paypal) > 0) {
                        $last = $paypal->last();

                    }
                    $stripedate = isset($data) && $data != null ? $data->created_at : null;
                    $paydate = isset($last) && $last != null ? $last->created_at : null;
                    if ($stripedate > $paydate) {
                        if ($user->subscribed($data->name)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::where($data->name)->first();
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    } elseif ($stripedate < $paydate) {
                        if (date($current_date) <= date($last->subscription_to)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::find($last->package_id);
                                if ($subscribe_plans->ads_in_app == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    }
                }
                return view('episodeplayer', compact('episode', 'season', 'user', 'remove_ads'));
            }

        }
    }

    public function paymentSuccess()
    {
        return view('thankyou');
    }
}
