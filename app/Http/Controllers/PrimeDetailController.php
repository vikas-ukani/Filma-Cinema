<?php

namespace App\Http\Controllers;

use App\Audio;
use App\Button;
use App\Config;
use App\LiveEvent;
use App\Movie;
use App\MovieSeries;
use App\MultipleLinks;
use App\Season;
use App\TvSeries;
use App\User;
use App\UserRating;
use Auth;
use Illuminate\Http\Request;


class PrimeDetailController extends Controller
{
   

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */

    public function __construct()
    {
        $this->button = Button::first();
    }

    public function showMovie($slug)
    {

        $movie = Movie::with(['menus' => function ($q) {
            return $q->select('movie_id', 'menu_id');
        }])->where('slug', $slug)->where('status', 1)->first();

        if(isset($movie) && $movie != NULL){
            $type_check = "M";
            $movies = Movie::all();
            $config = Config::findOrFail(1);
            $filter_series = collect();
            $age = 0;

            if (!isset($movie)) {
                return back()->with('deleted', __('Movie Is Not Available right now, Please comeback later !'));
            }

            if ($config->age_restriction == 1) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    $user = User::findOrfail($user_id);
                    $age = $user->age;
                }
            } else {
                $age = 100;
            }

            if ($this->button->countviews != 1) {
                views($movie)->record();
            }

            if ($movie->series == 1) {
                $single_series_list = MovieSeries::where('series_movie_id', $movie->id)->first();
                if (isset($single_series_list)) {

                    $main_movie_series = Movie::where('id', $single_series_list->movie_id)->first();
                    $filter_series->push($main_movie_series);
                    $series_list = (MovieSeries::where([['movie_id', $main_movie_series->id], ['series_movie_id', '!=', $movie->id]])->get());
                    foreach ($series_list as $item) {
                        $filter_movie_exc_self = Movie::where('id', $item->series_movie_id)->first();
                        $filter_series->push($filter_movie_exc_self);
                    }
                }
            }

            $movieRating = UserRating::where('movie_id', $movie->id)->get();

            if ($config->prime_movie_single == 1) {
                return view('movie_single_prime', compact('movie', 'movies', 'filter_series', 'type_check', 'age', 'config', 'movieRating'));
            } else {
                return view('movie_single', compact('movie', 'movies', 'filter_series', 'type_check', 'age', 'config', 'movieRating'));
            }
        }
        
    }

    public function showSeasons($season_slug)
    {

        $season = Season::where('season_slug', $season_slug)->first();
        if(isset($season) && $season != NULL){
            $type_check = "S";
            $movies = Movie::all();

            $config = Config::findOrFail(1);
            $age = 0;

            if ($this->button->countviews != 1) {
                views($season)->record();
            }

            if ($season->tvseries->status != 1) {
                return back()->with('deleted', __('This Season is not available right now, Please comeback later !'));
            }

            if ($config->age_restriction == 1) {
                if (Auth::check()) {
                    $user_id = Auth::user()->id;
                    $user = User::findOrfail($user_id);
                    $age = $user->age;
                }
            } else {
                $age = 100;
            }

            if ($config->prime_movie_single == 1) {
                return view('movie_single_prime', compact('season', 'age', 'movies', 'type_check', 'config'));
            } else {
                return view('movie_single', compact('season', 'age', 'movies', 'config', 'type_check'));
            }
        }else{
            abort(404);
        }
        
    }

    public function moviedownload($upload_video)
    {

        $file = $upload_video;

        $path = public_path() . "/movies_upload/" . $upload_video;
        $headers = array(
            'Content-Type : application/pdf',
        );
        return response()->download($path, $file, $headers);
    }

    public function seasondownload($upload_video)
    {

        $file = $upload_video;

        $path = public_path() . "/tvshow_upload/" . $upload_video;
        $headers = array(
            'Content-Type : application/pdf',
        );
        return response()->download($path, $file, $headers);

    }

    public function updateclick(Request $request)
    {
        $link = MultipleLinks::find($request->id);

        if (isset($link)) {
            $link->clicks = $link->clicks + 1;
            $link->save();

            return response()->json('Updated !');
        } else {
            return response()->json('Oops error');
        }
    }

    public function eventshow($slug)
    {
        $liveevent = LiveEvent::where('status', '=', '1')->where('slug', $slug)->first();
        if (!isset($liveevent)) {
            return abort(404);
        }

        return view('event_detail', compact('liveevent'));

    }

    public function audioshow($id)
    {
        $audio = Audio::where('id', $id)->first();
        if (!isset($audio)) {
            return abort(404);
        }

        return view('audio_detail', compact('audio'));

    }

}
