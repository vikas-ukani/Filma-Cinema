<?php

namespace App\Http\Controllers;

use App\HomeSlider;
use App\Movie;
use App\TvSeries;
use App\User;
use App\Genre;
use App\WatchHistory;
use App\Audio;
use App\AudioLanguage;
use App\Config;
use App\Button;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Customer;
use Illuminate\Support\Facades\Session;

class KidsSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        if (env('IS_INSTALLED') == 1) {
          
            $this->homeslider = HomeSlider::query();
            $this->configs = Config::first();
            $this->button = Button::first();
        }

    }

    public function index($id)
    {
        
        $auth = Auth::user();
        
        
        
        $home_slides = $this->homeslider->where('active',1)->where('is_kids',1)->orderBy('position', 'asc')->get();
        Session::put('kids_mode', 1);

        $catlog = $this->configs->catlog;

        $watchistory = WatchHistory::where('user_id', $auth->id)->get();
        $getallgenre = Genre::orderBy('position', 'ASC')->get();
        $getallaudiolanguage = AudioLanguage::get();
        $genres = Genre::select('id', 'name')->orderBy('position', 'ASC')->paginate(10);
        $audiolanguages = AudioLanguage::select('id', 'language')->paginate(10);
        $movies = Movie::where('status', '1')->where('is_kids',1)->orderBy('id', 'desc')->get();
        $tvseries = TvSeries::where('status', '1')->where('is_kids',1)->orderBy('id', 'desc')->get();
        $recent_tv = DB::table('tv_series')->where('is_kids',1)->orderBy('id', 'DESC')->get();

        $kids_mode = User::findorfail($id);

        if ($kids_mode->kids_mode_active == 1) {
            $kids_mode->kids_mode_active = 0;
        } else {
            $kids_mode->kids_mode_active = 1;
        }

        $kids_mode->save();
        return back()->with('updated');

        
       // return view('kids', compact( 'home_slides','movies','recent_tv', 'tvseries','watchistory','getallgenre','getallaudiolanguage','genres','audiolanguages','catlog'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
