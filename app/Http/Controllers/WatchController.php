<?php

namespace App\Http\Controllers;

use App\Audio;
use App\Button;
use App\Config;
use App\Episode;
use App\LiveEvent;
use App\Menu;
use App\Movie;
use App\Package;
use App\Season;
use App\TvSeries;
use App\User;
use App\WatchHistory;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Stripe\Customer;

class WatchController extends Controller
{
    public function __construct()
    {
        $this->button = Button::first();
    }

    public function watch($id)
    {
        $movie = Movie::find($id);
        return view('watch', compact('movie'));
    }

    public function watchtvtrailer($id)
    {
        $season = Season::find($id);
        return view('watchtv', compact('season'));
    }

    public function watchTV($id)
    {
        $pass = null;
        $season = Season::find($id);
        if ($season->is_protect == 1) {
            $password = Crypt::decrypt($season->password);
            $pass = md5($password);
        }

        if ($this->button->countviews == 1) {
            views($season)->record();
        }

        $remove_ads = 0;

        if (isset($auth)) {
            $current_date = date("d/m/y");
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
                    if ($auth->subscribed($data->name)) {
                        $subscribed = 1;
                        if ($this->button->remove_ads == 1) {
                            $subscribe_plans = Package::where($data->name)->first();
                            if ($subscribe_plans->ads_in_web == 1) {
                                $remove_ads = 1;
                            }
                        }
                    }
                } elseif ($stripedate < $paydate) {
                    if (date($current_date) <= date($last->subscription_to)) {
                        $subscribed = 1;
                        if ($this->button->remove_ads == 1) {
                            $subscribe_plans = Package::find($last->package_id);
                            if ($subscribe_plans->ads_in_web == 1) {
                                $remove_ads = 1;
                            }
                        }
                    }
                }
            }
        }

        return view('watchTvShow', compact('season', 'pass', 'remove_ads'));
    }

    public function watchMovie($id)
    {
        $pass = null;
        $movie = Movie::findorfail($id);
        $remove_ads = 0;
        if (checkInMovie($movie) == true) {
            if ($movie->is_protect == 1) {
                $password = Crypt::decrypt($movie->password);
                $pass = md5($password);
            }
            if ($this->button->countviews == 1) {
                views($movie)->record();
            }
            $auth = Auth::user();
            $subscribed = null;

            if (isset($auth)) {
                $current_date = date("d/m/y");
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
                        if ($auth->subscribed($data->name)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::where($data->name)->first();
                                if ($subscribe_plans->ads_in_web == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    } elseif ($stripedate < $paydate) {
                        if (date($current_date) <= date($last->subscription_to)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::find($last->package_id);
                                if ($subscribe_plans->ads_in_web == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    }
                }
            }

        } else {
            return redirect('/')->with('deleted', __('This video is not in your currenct subscription'));
        }

        return view('watchMovie', compact('movie', 'pass', 'remove_ads'));
    }

    public function watchEpisode($id)
    {
        $episode = Episode::find($id);
        $season = Season::find($episode->seasons_id);
        $remove_ads = 0;
        if (checkInTvseries($season->tvseries) == true) {
            if (isset($auth)) {
                $current_date = date("d/m/y");
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
                        if ($auth->subscribed($data->name)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::where($data->name)->first();
                                if ($subscribe_plans->ads_in_web == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    } elseif ($stripedate < $paydate) {
                        if (date($current_date) <= date($last->subscription_to)) {
                            $subscribed = 1;
                            if ($this->button->remove_ads == 1) {
                                $subscribe_plans = Package::find($last->package_id);
                                if ($subscribe_plans->ads_in_web == 1) {
                                    $remove_ads = 1;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            return back()->with('deleted', __('This video is not in your currenct subscription!'));
        }

        return view('episodeplayer', compact('episode', 'season', 'remove_ads'));
    }

    public function watchhistory(Request $request)
    {

        $auth = Auth::user()->id;
        $watchistory = WatchHistory::where('user_id', $auth)->get();
        $items = collect();

        foreach ($watchistory as $value) {
            if ($value->movie_id != '') {

                $items->push(Movie::find($value->movie_id));

            } else {

                $ts = TvSeries::find($value->tv_id);

                if (isset($ts)) {
                    $x = count($ts->seasons);

                    if ($x == 0) {

                    } else {
                        $items->push($ts->seasons[0]);
                    }
                }

            }
        }

        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $itemCollection = collect($items);

        // Define how many items we want to be visible in each page
        $perPage = 30;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generted links
        $paginatedItems->setPath($request->url());
        // return $paginatedItems;

        $menu = Menu::first();

        $age = 0;
        $config = Config::first();
        if ($config->age_restriction == 1) {
            if (Auth::user()) {
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            }

        } else {
            $age = 100;
        }

        return view('watchhistory', ['pusheditems' => $paginatedItems, 'menuu' => $menu, 'age' => $age]);

    }

    public function watchistorydelete()
    {

        $auth = Auth::user()->id;
        $history = WatchHistory::where('user_id', $auth)->delete();
        return redirect('/')->with('updated', __('Your Watch History Has Been Deleted'));

    }
    public function showdestroy($id)
    {

        $auth = Auth::user()->id;
        $show = WatchHistory::where('tv_id', $id)->where('user_id', $auth)->first();
        $show->delete();
        return back();
    }

    public function moviedestroy($id)
    {
        $auth = Auth::user()->id;
        $movie = WatchHistory::where('movie_id', $id)->where('user_id', $auth)->first();
        $movie->delete();
        return back();
    }
    public function watchEvent($id)
    {
        $liveevent = LiveEvent::find($id);
        if (getSubscription()->getData()->subscribed == true) {
            return view('watchEvent', compact('liveevent'));
        } else {
            return back()->with('deleted',__('This video is not in your currenct subscription!'));
        }

    }

    public function watchAudio($id)
    {
        $audio = Audio::find($id);
        $pass = null;
        if (getSubscription()->getData()->subscribed == true) {
            if ($audio->is_protect == 1) {
                $password = Crypt::decrypt($audio->password);
                $pass = md5($password);
            }

            return view('watchaudio', compact('audio', 'pass'));
        } else {
            return back()->with('deleted', __('This Audio is not in your current subscription!'));
        }

    }

    public function watchMovieiframe($id)
    {
        $movie = Movie::find($id);
        if (checkInMovie($movie) == true) {
            if ($this->button->countviews == 1) {
                views($movie)->record();
            }
            return view('watchMovieiframe', compact('movie'));
        } else {
            return back()->with('deleted', __('This video is not in your current subscription!'));
        }

    }

}
