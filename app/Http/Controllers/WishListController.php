<?php

namespace App\Http\Controllers;

use App\Adsense;
use App\Config;
use App\Menu;
use App\MenuVideo;
use App\Movie;
use App\Package;
use App\PaypalSubscription;
use App\Season;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WishListController extends Controller
{

    public function showWishLists($slug)
    {

        $auth = Auth::user();
        $type = "";
        $movies = collect();
        $watchlists = Wishlist::where('user_id', Auth::user()->id)->get()->count();

        $packageid = PaypalSubscription::select('package_id')->where('user_id', $auth->id)->get();
        if (isset($packageid)) {
            # code...

            foreach ($packageid as $package) {
                $packagename = Package::select('plan_id')->where('id', $package->package_id)->get();
            }
            if (isset($packagename)) {

                foreach ($packagename as $pn) {
                    $planmenus = DB::table('package_menu')->where('package_id', $pn->plan_id)->get();

                }
            }
            if (isset($planmenus)) {
                foreach ($planmenus as $key => $value) {
                    $menus[] = $value->menu_id;
                }
                $nav = Menu::whereIn('id', $menus)->orderBy('position', 'ASC')->get();
            }

            $userwish = Wishlist::where('user_id', $auth->id)->get();
            if (isset($userwish) && count($userwish) > 0) {

                foreach ($userwish as $key => $value) {
                    if (!is_null($value->movie_id) || !is_null($value->season_id)) {
                        $menuid = MenuVideo::where('movie_id', $value->movie_id)->get();
                        foreach ($menuid as $men => $menid) {

                            $menuname = Menu::where('id', $menid->menu_id)->orderBy('position', 'ASC')->get();
                            foreach ($menuname as $sl => $menuslug) {
                                if (strcmp($menuslug->slug, $slug) == 0) {

                                    $item = Movie::find($value->movie_id);
                                    $item2 = Season::find($value->season_id);
                                    if (isset($item)) {
                                        $movies->push($item);

                                    }if (isset($item2)) {
                                        $movies->push($item2);
                                    }
                                }
                            }
                        }

                    }
                }
            } else {
                return redirect('account/watchlist')->with('deleted', __('your Wishlist is Empty'));
            }
        } else {

            return redirect('account/watchlist')->with('deleted', __('your Wishlist is Empty'));
        }
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
        $movies = $movies->flatten();
        $movies = $movies->unique('id')->values()->all();
        $ad = Adsense::first();
        return view('watchlists', compact('movies', 'type', 'ad', 'age', 'watchlists'));
    }

    public function wishlistshow()
    {
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
        $menu = Menu::orderBy('position', 'ASC')->get();
        $ad = Adsense::first();
        $watchlists = Wishlist::where('user_id', Auth::user()->id)->get()->count();
        return view('watchlists', compact('menu', 'ad', 'age', 'watchlists'));
    }

    public function addWishList(Request $request)
    {
        $auth = Auth::user();
        $input['user_id'] = $auth->id;

        if ($request->type == 'M') {

            $wishlist = DB::table('wishlists')->where([
                ['user_id', '=', $auth->id],
                ['movie_id', '=', $request->id],
            ])->first();
            if (isset($wishlist) && $wishlist->added === 1) {
                DB::table('wishlists')->where([
                    ['user_id', '=', $auth->id],
                    ['movie_id', '=', $request->id],
                ])->update([
                    'added' => false,
                ]);
            } elseif (isset($wishlist) && $wishlist->added === 0) {
                DB::table('wishlists')->where([
                    ['user_id', '=', $auth->id],
                    ['movie_id', '=', $request->id],
                ])->update([
                    'added' => true,
                ]);
            } else {
                $input['movie_id'] = $request->id;
                $input['added'] = 1;
                Wishlist::create($input);
            }

        } elseif ($request->type === 'S') {

            $wishlist = DB::table('wishlists')->where([
                ['user_id', '=', $auth->id],
                ['season_id', '=', $request->id],
            ])->first();

            if (isset($wishlist) && $wishlist->added === 1) {
                DB::table('wishlists')->where([
                    ['user_id', '=', $auth->id],
                    ['season_id', '=', $request->id],
                ])->update([
                    'added' => false,
                ]);

            } elseif (isset($wishlist) && $wishlist->added === 0) {
                DB::table('wishlists')->where([
                    ['user_id', '=', $auth->id],
                    ['season_id', '=', $request->id],
                ])->update([
                    'added' => true,
                ]);
            } else {
                $input['season_id'] = $request->id;
                $input['added'] = 1;
                Wishlist::create($input);
            }
        }
    }

    public function showdestroy($id)
    {
        $auth = Auth::user()->id;
        $show = Wishlist::where('season_id', $id)->where('user_id', $auth)->first();
        $show->delete();
        return redirect('account/watchlist');
    }

    public function moviedestroy($id)
    {
        $auth = Auth::user()->id;
        $movie = Wishlist::where('movie_id', $id)->where('user_id', $auth)->first();
        $movie->delete();
        return redirect('account/watchlist');
    }
}
