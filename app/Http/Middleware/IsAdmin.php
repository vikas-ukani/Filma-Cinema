<?php

namespace App\Http\Middleware;

use App\AudioLanguage;
use App\Config;
use App\FrontSliderUpdate;
use App\Genre;
use App\HomeSlider;
use App\Menu;
use App\Movie;
use App\Package;
use App\Season;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $menus = Menu::all();
        $withlogin = Config::findOrFail(1)->withlogin;
        if (Auth::check()) {
            $auth = Auth::user();

            if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
                return $next($request);
            } else {
                return redirect('/');
            }

        } else {
            if ($withlogin == 1) {
                $navmenh = $request->route()->parameter('menu');
                if (isset($navmenh)) {
                    # code...

                    $sliderview = FrontSliderUpdate::all();

                    $home_slides = HomeSlider::orderBy('position', 'asc')->get();

                    $menu = Menu::whereSlug($navmenh)->first();
                    $withlogin = Config::findOrFail(1)->withlogin;
                    //Slider get limit here and Front Slider order
                    $catlog = Config::findOrFail(1)->catlog;
                    $limit = FrontSliderUpdate::where('id', 1)->first();

                    if (!isset($menu)) {

                        return redirect('/');
                    }

                    $movies = collect();
                    $fil_movies = $menu->menu_data;
                    if (count($fil_movies) > 0) {
                        foreach ($fil_movies as $key => $value) {

                            $movies->push($value->movie);

                        }
                    }

                    $movies = $movies->flatten();
                    $movies = $movies->filter(function ($value, $key) {
                        return $value != null;
                    });

                    $tvserieses = array();
                    // $tvserieses = collect();
                    $fil_tvserieses = $menu->menu_data;

                    //for desc order Movies

                    $limit2 = FrontSliderUpdate::where('id', 2)->first();

                    //for desc tvseries

                    $limit3 = FrontSliderUpdate::where('id', 3)->first();

                    if (count($fil_tvserieses) > 0) {

                        foreach ($fil_tvserieses as $key => $value) {

                            array_push($tvserieses, $value->tvseries);

                        }
                    }

                    $tvserieses = array_values(array_filter($tvserieses));

                    $genres = Genre::all();
                    $a_languages = AudioLanguage::all();
                    $all_mix = collect();

                    if (count($movies)) {
                        $mCount = 0;
                        foreach ($movies as $key => $value) {
                            if ($mCount < $limit->item_show) {
                                $all_mix->push($value);
                                $mCount++;
                            } else {
                                break;
                            }

                        }
                    }
                    // return $tvserieses;
                    if (count($tvserieses) > 0) {
                        $tCount = 0;
                        foreach ($tvserieses as $value) {
                            if ($value->type == 'T') {

                                if ($tCount < $limit->item_show) {
                                    $all_mix->push($value);
                                    $tCount++;
                                } else {
                                    break;
                                }

                            }
                        }
                    }

                    // Featured Movies Array
                    $featured_movies = collect();
                    if (count($movies) > 0) {
                        foreach ($movies as $key => $movie) {
                            if ($movie->featured == 1) {
                                $featured_movies->push($movie);
                            }
                        }
                    }

                    // Featured Tvserieses
                    $featured_seasons = collect();
                    if (count($tvserieses) > 0) {
                        foreach ($tvserieses as $key => $series) {
                            if ($series->featured == 1) {
                                $featured_seasons->push($series);
                            }
                        }
                    }
                    $featured_seasons = $featured_seasons->flatten()->shuffle();

                    $recent_added_movies = Movie::orderBy('id', 'desc')->get();
                    $recent_added_seasons = Season::orderBy('id', 'desc')->get();
                    $all_mix = $all_mix->shuffle();

                    if ($limit2->orderby == 0) {
                        $movies = $recent_added_movies;
                    }

                    if ($limit3->orderby == 0) {
                        arsort($tvserieses);
                    }

                    //limit for first section

                    $limitformix = FrontSliderUpdate::where('id', 1)->first();

                    $all_mix = $all_mix->chunk($limitformix->item_show);

                    if (count($all_mix) > 0) {
                        $all_mix = $all_mix[0];
                    }
                    $menuh = Menu::all();
                    $auth = Auth::user();
                    $subscribed = null;

                    if (isset($auth)) {
                        $current_date = date("d/m/y");

                        $auth = Auth::user();
                        if ($auth->is_admin == 1) {
                            $subscribed = 1;

                        } else if ($auth->stripe_id != null) {
                            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                            if (isset($invoices) && $invoices != null && count($invoices->data) > 0) {
                                $user_plan_end_date = date("d/m/y", $invoice->lines->data[0]->period->end);
                                $plans = Package::all();
                                foreach ($plans as $key => $plan) {
                                    if ($auth->subscriptions($plan->plan_id)) {
                                        if ($current_date <= $user_plan_end_date) {
                                            if ($auth->is_admin == 0) {
                                                $subscribed = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        } else if (isset($auth->paypal_subscriptions)) {
                            //Check Paypal Subscription of user
                            $last_payment = $auth->paypal_subscriptions->last();
                            if (isset($last_payment) && $last_payment->status == 1) {
                                //check last date to current date
                                $current_date = Carbon::now();
                                if (date($current_date) <= date($last_payment->subscription_to)) {
                                    $subscribed = 1;
                                }
                            }
                        }
                    }

                    return Response(view('home', compact('home_slides', 'recent_added_seasons',
                        'movies', 'tvserieses', 'a_languages', 'all_mix', 'sliderview', 'recent_added_movies',
                        'genres', 'featured_movies', 'featured_seasons', 'menuh', 'catlog', 'withlogin', 'subscribed', 'menu')));
                }
            } else {

                return redirect('login');

            }

        }
    }
}
