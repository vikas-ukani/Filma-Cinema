<?php
namespace App\Http\Controllers;

use App\Actor;
use App\Adsense;
use App\Audio;
use App\AudioLanguage;
use App\Blog;
use App\Button;
use App\Config;
use App\Director;
use App\Genre;
use App\HomeBlock;
use App\HomeSlider;
use App\LandingPage;
use App\LiveEvent;
use App\Menu;
use App\MenuSection;
use App\MenuVideo;
use App\Movie;
use App\Package;
use App\PackageFeature;
use App\PricingText;
use App\Season;
use App\TvSeries;
use App\User;
use App\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Customer;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
   
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        if (env('IS_INSTALLED') == 1) {
            $this->configs = Config::first();
            $this->menu_all = Menu::query();
            $this->g = Genre::query();
            $this->lang = AudioLanguage::query();
            $this->ad = Adsense::first();
            $this->homeslider = HomeSlider::query();
            $this->kids_homeslider = HomeSlider::query();
            $this->button = Button::first();
        }

    }

    public function showall(Request $request, $menuid, $menuname)
    {

        $ipaddress = $request->getClientIp();
        //$ipaddress='43.251.92.73'; 
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);

        $menu = Menu::with(['menu_data'])->findOrFail($menuid);

        $movies_ids = $menu->menu_data->pluck('movie_id')->all();

        $tv_series_ids = $menu->menu_data->pluck('tv_series_id')->all();

        $movies_ids = array_filter($movies_ids);

        $tv_series_ids = array_filter($tv_series_ids);

        $m = Movie::query();

        $tv = Tvseries::query();

        $movies = $m->wherein('id', $movies_ids)->with('video_link')->where('status', 1)->where('country', 'NOT like', '%'.$usercountry.'%');

        $series = $tv->wherein('id', $tv_series_ids)->where('status', 1)->where('country', 'NOT like', '%'.$usercountry.'%')
            ->whereHas('seasons_first')
            ->with(['seasons_first', 'seasons_first.firstEpisode', 'seasons_first.firstEpisode.video_link']);

        if ($request->age_rating != null) {
            if ($request->age_rating != "all") {

                $age = $request->age_rating . '+';

                $movies = $m->where('maturity_rating', '>=', $age);

                $series = $tv->where('maturity_rating', '>=', $age);
            }
        }

        if ($request->feature) {

            $movies = $m->where('featured', '=', 1);

            $series = $tv->where('featured', '=', 1);

        }

        if ($request->title != null) {

            $movies = $m->orderBy('title', $request->title);

            $series = $tv->orderBy('title', $request->title);

        }

        $movies = $m->with('menus')->get()->toArray();

        $series = $tv->with('menus')->get()->toArray();

        $finaldata = collect(array_merge_recursive($series, $movies));

        if ($request->genre != null) {
            $finaldata = $finaldata->map(function ($q) use ($request) {
                foreach ($request->genre as $generid) {

                    if (isset($q['genre_id']) && in_array($generid, explode(',', $q['genre_id']))) {
                        return $q;
                    }

                }
            });
        }

        $ad = $this->ad;

        $age = 0;

        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        return view('viewall', ['pusheditems' => $finaldata, 'menuu' => $menu, 'ad' => $ad, 'age' => $age]);

    }

    public function showallsinglemovies()
    {
        $ipaddress = $request->getClientIp();
        //$ipaddress='43.251.92.73'; 
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);

        $movies = Movie::orderBy('id', 'DESC')->where('status', '=', 1)->paginate(30);
        $ad = $this->ad;
        return view('viewall2', compact('movies', 'ad'));

    }

    public function showallsingletvseries(Request $request)
    {

        $ipaddress = $request->getClientIp();
        //$ipaddress='43.251.92.73'; 
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);
        $items = collect();

        $all_tvseries = TvSeries::where('status', '=', 1)->get();

        foreach ($all_tvseries as $series) {

            $x = count($series->seasons);

            if ($x == 0) {

            } else {
                $items->push($series->seasons[0]);
            }

        }

        $items = collect($items)->sortByDesc('id');

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
        $movies = $paginatedItems->setPath($request->url());

        $ad = $this->ad;
        return view('viewall2', compact('movies', 'ad'));
    }

    public function guestindex(Request $request, $menu_slug)
    {

        $age = 0;

        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            }
        } else {
            $age = 100;
        }

        $ipaddress = $request->getClientIp();
        //$ipaddress='43.251.92.73'; 
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);
        $home_slides = $this->homeslider->where('active',1)->orderBy('position', 'asc')->get();

        $menu = $this->menu_all->whereSlug($menu_slug)->first();
        $withlogin = $this->configs->withlogin;
        //Slider get limit here and Front Slider order
        $liveevent = LiveEvent::orderby('id', 'desc')->where('status', '1')->get();
        $audio = Audio::orderby('id', 'desc')->get();
        $blogs = Blog::where('is_active', '1')->get();
        $catlog = $this->configs->catlog;
        $protip = Button::find(1)->protip;

        if (!isset($menu)) {
            return redirect('/');
        }

        $menuh = $this->menu_all->orderBy('position', 'ASC')->get();
        $subscribed = null;

        $age = 0;
        $ad = $this->ad;

        $recent_data = DB::table('menu_videos')->where('menu_id', $menu->id)->orderBy('id', 'DESC')->get();
        $menu_data = DB::table('menu_videos')->where('menu_id', $menu->id)->get();

        $upcomingitems = Menu::whereSlug($menu_slug)->with(['menusections', 'menu_data' => function ($q) {
            return $q->where('movie_id', '!=', null);
        }])->whereHas('menu_data.movie', function ($q)use($usercountry) {

            return $q->where('is_upcoming', '!=', '0')->where('country', 'NOT like', '%'.$usercountry.'%');

        })->with(['menu_data.movie' => function ($q)use($usercountry) {
            return $q->where('is_upcoming', '!=', '0')->where('country', 'NOT like', '%'.$usercountry.'%');
        }])->first();

        $short_promo = HomeBlock::where('is_active', 1)->with(['movie', 'movie.video_link', 'tvseries', 'tvseries.seasons', 'tvseries.seasons.episodes', 'tvseries.seasons.episodes.video_link'])->get();

        $audio_lang = $this->lang->select('id', 'language')->with(['movie', 'movie.video_link', 'seasons', 'seasons.episodes', 'seasons.episodes.video_link'])->get();

        $getallgenre = Genre::orderBy('position', 'ASC')->get();
        $genres = Genre::select('id', 'name')->orderBy('position', 'ASC')->paginate(10);
        $audiolanguages = AudioLanguage::select('id', 'language')->paginate(10);
        $section6 = MenuSection::where('section_id', '=', 6)->where('menu_id', '=', $menu->id)->first();
        $section = MenuSection::where('section_id', '=', 2)->where('menu_id', '=', $menu->id)->first();

        $top_data = Menu::whereSlug($menu_slug)
            ->whereHas('menu_data')
            ->whereHas('menusections')
            ->whereHas('menu_data.movie')
            ->orWhereHas('menu_data.tvseries')
            ->with(['menu_data', 'menu_data.movie', 'menu_data.tvseries', 'menu_data.tvseries.seasons',
            ])->first();

        $getallaudiolanguage = AudioLanguage::get();
        if ($this->configs->prime_genre_slider == 1) {
            //Layout 1
            return view('home', compact('getallgenre','protip', 'menu_data', 'recent_data', 'ad', 'age', 'genres', 'menuh', 'catlog', 'withlogin', 'menu', 'subscribed', 'audiolanguages', 'section6', 'blogs', 'liveevent', 'home_slides', 'age', 'audio', 'short_promo', 'audio_lang', 'upcomingitems', 'getallaudiolanguage', 'top_data'));
        } else {
            //Layout 2
            return view('home2', compact('getallgenre','protip', 'menu_data', 'recent_data', 'ad', 'age', 'genres', 'menuh', 'catlog', 'withlogin', 'menu', 'subscribed', 'audiolanguages', 'section6', 'blogs', 'liveevent', 'home_slides', 'age', 'audio', 'short_promo', 'audio_lang', 'upcomingitems', 'getallaudiolanguage', 'top_data'));
        }

    }

    public function index(Request $request, $menu_slug)
    {

        $auth = Auth::user();
        $subscribed = null;
        if (isset($auth)) {
            $current_date = date("d/m/y");
            if ($auth->is_admin == 1 || $auth->is_assistant == 1) {
                $subscribed = 1;

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
                    }
                } elseif ($stripedate < $paydate) {
                    if (date($current_date) <= date($last->subscription_to)) {
                        $subscribed = 1;
                    }
                }
            }
        }

        
        $home_slides = $this->homeslider->where('active',1)->where('is_kids',0)->orderBy('position', 'asc')->get();
        
        
        $subscribe = $menu = Menu::whereSlug($menu_slug)->first();
        $withlogin = $this->configs->withlogin;
        //Slider get limit here and Front Slider order

        $blogs = Blog::where('is_active', '1')->get();
        $catlog = $this->configs->catlog;
        $protip = Button::find(1)->protip;
        
        
        $ipaddress = $request->getClientIp();
        //$ipaddress='43.251.92.73'; 
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);


        $watchistory = WatchHistory::where('user_id', $auth->id)->get();

        $menuh = $this->menu_all;

        $ad = $this->ad;

        $age = 0;

        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        if (!isset($menu)) {
            return redirect('/');
        }

        

        $menu_data = DB::table('menu_videos')->where('menu_id', $menu->id)->get();
        $recent_data = DB::table('menu_videos')->where('menu_id', $menu->id)->orderBy('id', 'DESC')->get();

        $upcomingitems = $this->menu_all->whereSlug($menu_slug)->with(['menusections', 'menu_data' => function ($q) {
            return $q->where('movie_id', '!=', null);
        }])->whereHas('menu_data.movie', function ($q)use($usercountry) {

            return $q->where('is_upcoming', '!=', '0')->where('country', 'NOT like', '%'.$usercountry.'%');

        })->with(['menu_data.movie' => function ($q)use($usercountry) {
           //return $q->where('is_upcoming', '!=', '0')->where('country', '!=', 'abc');
           return $q->where('is_upcoming', '!=', '0')->where('country', 'NOT like', '%'.$usercountry.'%');
         //return $q->where('is_upcoming', '!=', '0')->whereNotIn('country', [$usercountry]);
         //return $q->where('is_upcoming', '!=', '0')->whereRaw(FIND_IN_SET($usercountry, country));
        }])->first();

        $short_promo = HomeBlock::where('is_active', 1)->with(['movie', 'movie.video_link', 'tvseries', 'tvseries.seasons', 'tvseries.seasons.episodes', 'tvseries.seasons.episodes.video_link'])->get();
        $audio_lang = $this->lang->with(['movie', 'movie.video_link', 'seasons', 'seasons.episodes', 'seasons.episodes.video_link'])->get();

        $liveevent = LiveEvent::orderby('id', 'desc')->where('status', '1')->get();
        $audio = Audio::orderby('id', 'desc')->get();
        $getallgenre = Genre::orderBy('position', 'ASC')->get();
        $getallaudiolanguage = AudioLanguage::get();
        $genres = Genre::select('id', 'name')->orderBy('position', 'ASC')->paginate(10);
        $audiolanguages = AudioLanguage::select('id', 'language')->paginate(10);
        $section6 = MenuSection::where('section_id', '=', 6)->where('menu_id', '=', $menu->id)->first();
        $section = MenuSection::where('section_id', '=', 2)->where('menu_id', '=', $menu->id)->first();
        $top_data = Menu::whereSlug($menu_slug)
            ->whereHas('menu_data')
            ->whereHas('menusections')
            ->whereHas('menu_data.movie')
            ->orWhereHas('menu_data.tvseries')
            ->with(['menu_data', 'menu_data.movie', 'menu_data.tvseries', 'menu_data.tvseries.seasons',
            ])->first();
         

        //kids_mode
        $kids_home_slides = $this->kids_homeslider->where('active',1)->where('is_kids',1)->orderBy('position', 'asc')->get();
        Session::put('kids_mode', 1);

        $catlog = $this->configs->catlog;

        $kids_watchistory = WatchHistory::where('user_id', $auth->id)->get();
        $kids_getallgenre = Genre::orderBy('position', 'ASC')->get();
        $kids_getallaudiolanguage = AudioLanguage::get();
        $kids_genres = Genre::select('id', 'name')->orderBy('position', 'ASC')->paginate(10);
        $kids_audiolanguages = AudioLanguage::select('id', 'language')->paginate(10);
        $kids_movies = Movie::where('status', '1')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'desc')->get();
        $kids_tvseries = TvSeries::where('status', '1')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'desc')->get();
        $kids_recent_tv = DB::table('tv_series')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'DESC')->get();
        if (Auth::user()->kids_mode_active == 1){
            return view('kids', compact( 'kids_home_slides','kids_movies','kids_recent_tv', 'kids_tvseries','kids_watchistory','kids_getallgenre','kids_getallaudiolanguage','kids_genres','kids_audiolanguages','catlog'));
        }

        elseif ($this->configs->prime_genre_slider == 1) {
            //Layout 1
            //return $usercountry;
            return view('home', compact('menu_data', 'recent_data', 'home_slides', 'ad', 'age', 'genres', 'menuh', 'catlog', 'withlogin', 'menu', 'subscribed', 'watchistory', 'audiolanguages', 'section6', 'getallgenre', 'blogs', 'liveevent', 'protip', 'audio', 'short_promo', 'audio_lang', 'upcomingitems', 'getallaudiolanguage', 'top_data'));
        } else {
            //Layout 2
            return view('home2', compact('menu_data', 'recent_data', 'home_slides', 'ad', 'age', 'genres', 'menuh', 'catlog', 'withlogin', 'menu', 'subscribed', 'watchistory', 'audiolanguages', 'section6', 'getallgenre', 'blogs', 'liveevent', 'protip', 'audio', 'short_promo', 'audio_lang', 'upcomingitems', 'getallaudiolanguage', 'top_data'));
        }

    }

    public function mainPage(Request $request)
    {
        //return ($request);
        $plans = Package::all();
        $pricingTexts = PricingText::all();
        $blocks = LandingPage::orderBy('position', 'asc')->get();
        $package_feature = PackageFeature::get();
        $catlog = $this->configs->catlog;
        $removelanding = $this->configs->remove_landing_page;
        $withlogin = $this->configs->withlogin;
        $menufirst = Menu::first();

         $ipaddress = $request->getClientIp();
         //$ipaddress='43.251.92.73'; 
         $geoip = geoip()->getLocation($ipaddress);
         $usercountry = strtoupper($geoip->country);
        //kids_mode
        $auth = Auth::user();
        $kids_home_slides = $this->kids_homeslider->where('active',1)->where('is_kids',1)->orderBy('position', 'asc')->get();
        Session::put('kids_mode', 1);

        $catlog = $this->configs->catlog;

        $kids_getallgenre = Genre::orderBy('position', 'ASC')->get();
        $kids_getallaudiolanguage = AudioLanguage::get();
        $kids_genres = Genre::select('id', 'name')->orderBy('position', 'ASC')->paginate(10);
        $kids_audiolanguages = AudioLanguage::select('id', 'language')->paginate(10);
        $kids_movies = Movie::where('status', '1')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'desc')->get();
        $kids_tvseries = TvSeries::where('status', '1')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'desc')->get();
        $kids_recent_tv = DB::table('tv_series')->where('is_kids',1)->where('country', 'NOT like', '%'.$usercountry.'%')->orderBy('id', 'DESC')->get();
        
        if (Auth::check() && Auth::user()->kids_mode_active == 1){
            return view('kids', compact( 'kids_home_slides','kids_movies','kids_recent_tv', 'kids_tvseries','kids_getallgenre','kids_getallaudiolanguage','kids_genres','kids_audiolanguages','catlog'));
        }
        elseif ($removelanding == 1 && $catlog == 1) {
            if (isset($menufirst->slug)) {
                if (Auth::check()) {
                    return redirect()->route('home', $menufirst->slug);
                } else {
                    return redirect()->route('guests', $menufirst->slug);
                }

            } else {
                return view('auth.login');
            }

        } else if ($removelanding == 1 && $catlog == 0) {
            return view('auth.login');
        } else {
            if ($catlog == 1 && $withlogin == 0) {

                $menuh = Menu::get();
                return view('main', compact('pricingTexts', 'plans', 'blocks', 'menuh', 'catlog', 'withlogin', 'package_feature'));
            } else if ($catlog == 1 && $withlogin == 1) {

                $menuh = Menu::get();
                return view('main', compact('pricingTexts', 'plans', 'blocks', 'menuh', 'catlog', 'withlogin', 'package_feature'));
            } else {

                return view('main', compact('pricingTexts', 'plans', 'blocks', 'catlog', 'withlogin', 'package_feature'));

            }
        }

    }

    public function showallgenre(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (isset($genre)) {
            $items = collect();
            $movies = Movie::where('genre_id', 'LIKE', '%' . $genre->id . '%')->where('status', 1)->get();

            foreach ($movies as $movie) {
                $items->push($movie);
            }

            $tvs = TvSeries::where('genre_id', 'LIKE', '%' . $genre->id . '%')->where('status', 1)->whereHas('seasons_first')->get();

            foreach ($tvs as $tv) {
                $items->push($tv);
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

            $ad = Adsense::first();

            $age = 0;
            if ($this->configs->age_restriction == 1) {
                if (Auth::user()) {
                    # code...
                    $user_id = Auth::user()->id;
                    $user = User::find($user_id);
                    $age = $user->age;
                } else {
                    $age = 100;
                }
            }

            return view('showallgenre', ['pusheditems' => $paginatedItems, 'ad' => $ad, 'genre' => $genre, 'age' => $age]);

        } else {
            return abort(404, 'Genre not found !');
        }
    }

    public function showallalang(Request $request, $id)
    {
        $alang = AudioLanguage::find($id);

        if (isset($alang)) {
            $items = collect();
            $movies = Movie::where('a_language', 'LIKE', '%' . $alang->id . '%')->where('status', 1)->get();

            foreach ($movies as $movie) {
                $items->push($movie);
            }

            $tvs = Season::where('a_language', 'LIKE', '%' . $alang->id . '%')->get();

            foreach ($tvs as $tv) {
                $items->push($tv);
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

            $ad = Adsense::first();

            $age = 0;
            if ($this->configs->age_restriction == 1) {
                if (Auth::user()) {
                    # code...
                    $user_id = Auth::user()->id;
                    $user = User::find($user_id);
                    $age = $user->age;
                } else {
                    $age = 100;
                }
            }

            return view('showallalang', ['pusheditems' => $paginatedItems, 'ad' => $ad, 'alang' => $alang, 'age' => $age]);

        } else {
            return abort(404, 'Language not found !');
        }
    }

    public function gusetshowallgenre(Request $request, $id)
    {

        $genre = Genre::find($id);

        if (isset($genre)) {
            $items = collect();
            $movies = Movie::where('genre_id', 'LIKE', '%' . $genre->id . '%')->where('status', 1)->get();

            foreach ($movies as $movie) {
                $items->push($movie);
            }

            $tvs = TvSeries::where('genre_id', 'LIKE', '%' . $genre->id . '%')->where('status', 1)->whereHas('seasons_first')->get();

            foreach ($tvs as $tv) {
                $items->push($tv);
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

            $ad = $this->ad;

            $age = 0;
            if ($this->configs->age_restriction == 1) {
                if (Auth::user()) {
                    # code...
                    $user_id = Auth::user()->id;
                    $user = User::find($user_id);
                    $age = $user->age;
                } else {
                    $age = 100;
                }
            }

            return view('showallgenre', ['pusheditems' => $paginatedItems, 'ad' => $ad, 'genre' => $genre, 'age' => $age]);

        } else {
            return abort(404, 'Genre not found !');
        }
    }

    public function guestshowallalang(Request $request, $id)
    {
        $alang = $this->lang->find($id);

        if (isset($alang)) {
            $items = collect();
            $movies = Movie::where('a_language', 'LIKE', '%' . $alang->id . '%')->where('status', 1)->get();

            foreach ($movies as $movie) {
                $items->push($movie);
            }

            $tvs = Season::where('a_language', 'LIKE', '%' . $alang->id . '%')->get();

            foreach ($tvs as $tv) {
                $items->push($tv);
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

            $ad = Adsense::first();

            $age = 0;

            return view('showallalang', ['pusheditems' => $paginatedItems, 'ad' => $ad, 'alang' => $alang, 'age' => $age]);

        } else {
            return abort(404, 'Language not found !');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function search(Request $searchKey)
    {
        $age = null;
        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        $all_movies = Movie::where('status', '1')->get();
        $all_tvseries = TvSeries::where('status', '1')->get();
        $searchKey = $searchKey->search;

        $tvseries = TvSeries::where('title', 'LIKE', "%$searchKey%")->where('status', 1)->get();
        $filter_video = collect();

        foreach ($tvseries as $series) {
            $menuid = MenuVideo::where('tv_series_id', $series->id)
                ->get();

            if (isset($menus) && count($menus) > 0) {
                foreach ($menuid as $key => $value) {
                    for ($i = 0; $i < sizeof($menus); $i++) {
                        if ($value->menu_id == $menus[$i]) {
                            $season = Season::where('tv_series_id', $series->id)
                                ->get();
                            if (isset($season)) {
                                $filter_video->push($season[0]);
                            }
                        }
                    }

                }
            } else {
                $season = Season::where('tv_series_id', $series->id)->get();
                if (isset($season)) {
                    $filter_video->push($season[0]);
                }
            }

        }

        $movies = Movie::where('title', 'LIKE', "%$searchKey%")->where('status', '=', 1)->get();

        if (isset($movies) && count($movies) > 0) {
            foreach ($movies as $key => $movie) {
                $menuid = MenuVideo::where('movie_id', $movie->id)
                    ->get();
                if (isset($menus) && count($menus) > 0) {
                    foreach ($menuid as $key => $value) {
                        for ($i = 0; $i < sizeof($menus); $i++) {
                            if ($value->menu_id == $menus[$i]) {
                                $filter_video->push($movies);
                            }
                        }

                    }
                } else {
                    $filter_video->push($movies);
                }

            }

        }

        // if search key is actor
        $actor = Actor::where('name', 'LIKE', "%$searchKey%")->first();
        if (isset($actor) && $actor != null) {
            foreach ($all_movies as $key => $item) {
                if ($item->actor_id != null && $item->actor_id != '') {
                    $movie_actor_list = explode(',', $item->actor_id);
                    for ($i = 0; $i < count($movie_actor_list); $i++) {
                        $check = Actor::where('id', '=', trim($movie_actor_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $actor->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
            foreach ($all_tvseries as $key => $tv) {
                foreach ($tv->seasons as $key => $item) {
                    if ($item->actor_id != null && $item->actor_id != '') {
                        $season_actor_list = explode(',', $item->actor_id);
                        for ($i = 0; $i < count($season_actor_list); $i++) {
                            $check = Actor::where('id', '=', trim($season_actor_list[$i]))->get();
                            if (isset($check[0]) && $check[0]->name == $actor->name) {
                                $filter_video->push($item);
                            }
                        }
                    }
                }
            }
        }

        // if search key is director
        $director = Director::where('name', 'LIKE', "%$searchKey%")->first();
        if (isset($director) && $director != null) {
            foreach ($all_movies as $key => $item) {
                if ($item->director_id != null && $item->director_id != '') {
                    $movie_director_list = explode(',', $item->director_id);
                    for ($i = 0; $i < count($movie_director_list); $i++) {
                        $check = Director::where('id', '=', trim($movie_director_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $director->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
        }

        // if search key is genre
        $genre = Genre::where('name', 'LIKE', "%$searchKey%")->first();

        if (isset($genre) && $genre != null) {
            foreach ($all_movies as $key => $item) {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $movie_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($movie_genre_list); $i++) {
                        $check = Genre::where('id', '=', trim($movie_genre_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $genre->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }

            foreach ($all_tvseries as $key => $item) {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $tv_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($tv_genre_list); $i++) {
                        $check = Genre::where('id', '=', trim($tv_genre_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $genre->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
        }

        $filter_video = $filter_video->flatten();

        return view('search', compact('filter_video', 'searchKey', 'age'));
    }

    public function quicksearch(Request $request)
    {

        $search = $request->search;
        $result = array();

        $searchinmovie = DB::table('movies')->where('title', 'LIKE', '%' . $search . '%')->select('id', 'title', 'slug')->get();

        $searchintvshow = DB::table('tv_series')->where('title', 'LIKE', '%' . $search . '%')->select('id', 'title')->get();

        foreach ($searchinmovie as $key => $value) {

            if (Auth::check()) {

                $result[] = ['id' => $value->id, 'value' => $value->title, 'url' => url('movie/detail/' . $value->slug)];
            } else {
                $result[] = ['id' => $value->id, 'value' => $value->title, 'url' => url('movie/guest/detail/' . $value->slug)];
            }

        }

        foreach ($searchintvshow as $key => $tvshow) {
            $season = Season::where('tv_series_id', '=', $tvshow->id)->first();
            if (isset($season) && $season != null) {
                if (Auth::check()) {

                    $result[] = ['id' => $tvshow->id, 'value' => $tvshow->title, 'url' => url('show/detail/' . $season->season_slug)];
                } else {
                    $result[] = ['id' => $tvshow->id, 'value' => $tvshow->title, 'url' => url('show/guest/detail/' . $season->season_slug)];
                }
            }

        }

        if (count($result) < 1) {
            $result[] = ['id' => 1, 'value' => 'No Result found !', 'url' => '#'];
        }

        return response()->json($result);

    }

    public function director_search($director_search)
    {
        $age = null;
        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        $filter_video = collect();
        $all_movies = Movie::where('status', '1')->get();
        $tvseries = TvSeries::where('status', '1')->get();
        $searchKey = $director_search;
        $director = Director::where('slug', 'LIKE', "%$director_search%")->orWhere('name', 'LIKE', "%$director_search%")->first();

        if ($searchKey != null || $searchKey != '') {
            foreach ($all_movies as $item) {
                if ($item->director_id != null && $item->director_id != '') {
                    $movie_director_list = explode(',', $item->director_id);
                    for ($i = 0; $i < count($movie_director_list); $i++) {
                        $check = Director::where('id', '=', trim($movie_director_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $director->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
        }

        $filter_video = $filter_video->filter(function ($value, $key) {
            return $value != null;
        });

        $filter_video = $filter_video->flatten();
        return view('search', compact('filter_video', 'searchKey', 'director', 'age'));
    }

    public function actor_search($actor_search)
    {
        $age = null;
        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::find($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        $filter_video = collect();
        $all_movies = Movie::where('status', '1')->get();
        $tvseries = TvSeries::where('status', '1')->get();
        $searchKey = $actor_search;
        $actor = Actor::where('slug', 'LIKE', "%$actor_search%")->orWhere('name', 'LIKE', "%$actor_search%")->first();

        if ($searchKey != null || $searchKey != '') {
            foreach ($all_movies as $item) {
                if ($item->actor_id != null && $item->actor_id != '') {
                    $movie_actor_list = explode(',', $item->actor_id);
                    for ($i = 0; $i < count($movie_actor_list); $i++) {
                        $check = Actor::where('id', '=', trim($movie_actor_list[$i]))->get();
                        if (isset($check[0]) && isset($check[0]->name) && $check[0]->name == $actor->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }
            if (isset($tvseries) && count($tvseries) > 0) {
                foreach ($tvseries as $series) {
                    if (isset($series->seasons) && count($series->seasons) > 0) {
                        foreach ($series->seasons as $item) {
                            if ($item->actor_id != null && $item->actor_id != '') {
                                $season_actor_list = explode(',', $item->actor_id);
                                for ($i = 0; $i < count($season_actor_list); $i++) {
                                    $check = Actor::where('id', '=', trim($season_actor_list[$i]))->get();
                                    if (isset($check[0]) && $check[0]->name == $actor->name) {
                                        $filter_video->push($item);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $filter_video = $filter_video->filter(function ($value, $key) {
            return $value != null;
        });

        $filter_video = $filter_video->flatten();
        return view('search', compact('filter_video', 'searchKey', 'actor', 'age'));
    }

    public function genre_search($genre_search)
    {

        $age = null;
        if ($this->configs->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        $all_genres = Genre::get();
        $all_movies = Movie::where('status', '1')->get();
        $all_tvseries = TvSeries::where('status', '1')->get();
        $filter_video = collect();

        if (isset($all_genres) && $all_genres != null) {
            foreach ($all_genres as $key => $value) {
                if (trim($value->name) == trim($genre_search)) {
                    $genre = $value;
                }
            }
        }

        $searchKey = $genre_search;
        if ($genre != null) {
            foreach ($all_movies as $item) {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $movie_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($movie_genre_list); $i++) {
                        $check = Genre::where('id', '=', trim($movie_genre_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $genre->name) {
                            $filter_video->push($item);
                        }
                    }
                }
            }

            if (isset($all_tvseries) && count($all_tvseries) > 0) {
                foreach ($all_tvseries as $series) {
                    if (isset($series->seasons) && count($series->seasons) > 0) {
                        if ($series->genre_id != null && $series->genre_id != '') {
                            $tvseries_genre_list = explode(',', $series->genre_id);
                            for ($i = 0; $i < count($tvseries_genre_list); $i++) {
                                $check = Genre::where('id', '=', trim($tvseries_genre_list[$i]))->get();
                                if (isset($check[0]) && $check[0]->name == $genre->name) {
                                    $filter_video->push($series->seasons);
                                }
                            }
                        }
                    }
                }
            }
        }

        $filter_video = $filter_video->filter(function ($value, $key) {
            return $value != null;
        });

        $filter_video = $filter_video->flatten();

        return view('search', compact('filter_video', 'searchKey', 'age'));
    }

    public function movie_genre($id)
    {
        $all_movies = Movie::where('status', '1')->get();
        $movies = collect();
        $genre = Genre::find($id);
        $searchKey = $genre->name;
        foreach ($all_movies as $item) {
            if ($item->imdb != 'Y') {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $movie_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($movie_genre_list); $i++) {
                        $check = Genre::find(trim($movie_genre_list[$i]));
                        if (isset($check) && $check->id == $genre->id) {
                            $movies->push($item);
                        }
                    }
                }
            } else {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $movie_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($movie_genre_list); $i++) {
                        $check = Genre::where('id', '=', trim($movie_genre_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $genre->name) {
                            $movies->push($item);
                        }
                    }
                }
            }
        }

        $filter_video = $movies;

        return view('search', compact('filter_video', 'searchKey'));
    }

    public function tvseries_genre($id)
    {
        $all_tvseries = TvSeries::where('status', '1')->get();
        $genre = Genre::find($id);
        $searchKey = $genre->name;
        $seasons = collect();
        foreach ($all_tvseries as $item) {
            if ($item->imdb != 'Y') {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $tvseries_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($tvseries_genre_list); $i++) {
                        $check = Genre::find(trim($tvseries_genre_list[$i]));
                        if (isset($check) && $check->id == $genre->id) {
                            $seasons->push($item->seasons);
                        }
                    }
                }
            } else {
                if ($item->genre_id != null && $item->genre_id != '') {
                    $tvseries_genre_list = explode(',', $item->genre_id);
                    for ($i = 0; $i < count($tvseries_genre_list); $i++) {
                        $check = Genre::where('id', '=', trim($tvseries_genre_list[$i]))->get();
                        if (isset($check[0]) && $check[0]->name == $genre->name) {
                            $seasons->push($item->seasons);
                        }
                    }
                }
            }
        }

        $filter_video = $seasons->shuffle()->flatten();
        return view('search', compact('filter_video', 'searchKey'));
    }

    public function movie_language($language_id)
    {
        $lang = AudioLanguage::findOrFail($language_id);
        $searchKey = $lang->language;
        $all_movies = Movie::where('status', '1')->get();
        $filter_video = collect();
        foreach ($all_movies as $item) {
            if ($item->a_language != null && $item->a_language != '') {
                $movie_lang_list = explode(',', $item->a_language);
                for ($i = 0; $i < count($movie_lang_list); $i++) {
                    $check = AudioLanguage::find(trim($movie_lang_list[$i]));
                    if (isset($check) && $check->id == $lang->id) {
                        $filter_video->push($item);
                    }
                }
            }
        }

        return view('search', compact('filter_video', 'searchKey'));
    }

    public function tvseries_language($language_id)
    {
        $lang = AudioLanguage::findOrFail($language_id);
        $searchKey = $lang->language;
        $all_seasons = Season::all();
        $filter_video = collect();
        foreach ($all_seasons as $item) {
            if ($item->a_language != null && $item->a_language != '') {
                $season_lang_list = explode(',', $item->a_language);
                for ($i = 0; $i < count($season_lang_list); $i++) {
                    $check = AudioLanguage::find(trim($season_lang_list[$i]));
                    if (isset($check) && $check->id == $lang->id) {
                        $filter_video->push($item);
                    }
                }
            }
        }

        return view('search', compact('filter_video', 'searchKey'));
    }

}
