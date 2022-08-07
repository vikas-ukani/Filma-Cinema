<?php
namespace App\Http\Controllers;

use App\Actor;
use App\AudioLanguage;
use App\Director;
use App\Genre;
use App\HomeSlider;
use App\Label;
use App\Menu;
use App\MenuVideo;
use App\Movie;
use App\Allcountry;
use App\MovieComment;
use App\MovieSeries;
use App\MultipleLinks;
use App\Subtitles;
use App\User;
use App\Videolink;
use App\WatchHistory;
use App\Wishlist;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Laravolt\Avatar\Avatar;


class MovieController extends Controller
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Function to get the client IP address
    function get_client_ip() {

        //$ipaddress='43.251.92.73'; 
        $ipaddress = $request->getClientIp();
        $geoip = geoip()->getLocation($ipaddress);
        $usercountry = strtoupper($geoip->country);
        return $usercountry;
    }
    public function __construct()
    {
        $this->middleware('permission:movies.view', ['only' => ['index', 'multiplelinks']]);
        $this->middleware('permission:movies.create', ['only' => ['create', 'store', 'storlink']]);
        $this->middleware('permission:movies.edit', ['only' => ['edit', 'update', 'editlink']]);
        $this->middleware('permission:movies.delete', ['only' => ['destroy', 'bulk_delete', 'deletelink']]);
    }

    public function index(Request $request)
    {

        if (Auth::user()->is_assistant == 1) {
            if ($request->search != null) {
                $movies = DB::table('movies')->where('title', 'like', '%' . $request->search . '%')->select('id', 'slug', 'title', 'thumbnail', 'poster', 'rating', 'tmdb', 'featured', 'is_kids', 'status', 'created_by', 'publish_year', 'genre_id', 'duration')
                    ->where('live', 0)
                    ->where('created_by', Auth::user()
                            ->id)
                        ->orderBy('id', 'DESC')
                    ->paginate(12);
            } else {
                $movies = DB::table('movies')->select('id', 'slug', 'title', 'thumbnail', 'poster', 'rating', 'tmdb', 'featured', 'status', 'is_kids', 'created_by', 'publish_year', 'genre_id', 'duration')
                    ->where('live', 0)
                    ->where('created_by', Auth::user()
                            ->id)
                        ->orderBy('id', 'DESC')
                    ->paginate(12);
            }

        } else {
            if ($request->search != null) {
                $movies = DB::table('movies')->where('title', 'like', '%' . $request->search . '%')->select('id', 'slug', 'title', 'thumbnail', 'poster', 'rating', 'tmdb', 'featured', 'is_kids', 'status', 'created_by', 'publish_year', 'genre_id', 'duration')
                    ->where('live', 0)->orderBy('id', 'DESC')
                    ->paginate(12);
            } else {
                $movies = DB::table('movies')->select('id', 'slug', 'title', 'thumbnail', 'poster', 'rating', 'tmdb', 'featured', 'status', 'is_kids', 'created_by', 'publish_year', 'genre_id', 'duration')
                    ->where('live', 0)->orderBy('id', 'DESC')
                    ->paginate(12);
            }
        }
//->where('status', 1)
        return view('admin.movie.index', compact('movies'));
    }

    public function addedMovies(Request $request)
    {

        $movies = DB::table('movies')->select('id', 'slug', 'title', 'thumbnail', 'poster', 'rating', 'tmdb', 'featured', 'status','is_kids', 'created_by')
            ->where('live', 0)
            ->where('status', '=', 0)
            ->get();

        if ($request->ajax()) {
            return \Datatables::of($movies)->addIndexColumn()->addColumn('checkbox', function ($movies) {
                $html = '<div class="inline">
                <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="' . $movies->id . '" id="checkbox' . $movies->id . '">
                <label for="checkbox' . $movies->id . '" class="material-checkbox"></label>
                </div>';

                return $html;
            })->addColumn('thumbnail', function ($movies) {
                if ($movies->thumbnail) {
                    $thumnail = '<img src="' . asset('/images/movies/thumbnails/' . $movies->thumbnail) . '" alt="Pic" width="70px" class="img-responsive">';
                } else if ($movies->poster) {
                    $thumnail = '<img src="' . asset('/images/movies/posters/' . $movies->poster) . '" alt="Pic" width="70px" class="img-responsive">';
                } else {
                    $thumnail = '<img  src=' . Avatar::create($movies->title)->toBase64() . ' alt="Pic" width="70px" class="img-responsive">';
                }

                return $thumnail;

            })->addColumn('rating', function ($movies) {

                return 'IMDB ' . $movies->rating;
            })->addColumn('featured', function ($movies) {
                if ($movies->featured == 1) {
                    $featured = 'Y';
                } else {
                    $featured = '-';
                }
                return $featured;
            })->addColumn('tmdb', function ($movies) {
                if ($movies->tmdb == 'Y') {
                    $tmdb = '<i class="material-icons done">done</i>';
                } else {
                    $tmdb = '-';
                }
                return $tmdb;
            })->addColumn('addedby', function ($movies) {
                $username = User::find($movies->created_by);

                if (isset($username)) {
                    return $username->name;
                } else {
                    return 'User deleted';
                }

            })->addColumn('status', function ($movies) {
                if ($movies->status == 1) {
                    return "<a href=" . route('quick.movie.status', $movies->id) . " class='btn btn-sm btn-success'>" . __('adminstaticwords.Active') . "</a>";
                } else {
                    return "<a href=" . route('quick.movie.status', $movies->id) . " class='btn btn-sm btn-danger'>" . __('adminstaticwords.Deactive') . "</a>";
                }
            })->addColumn('action', function ($movies) {
                if ($movies->status == 1) {
                    $btn = ' <div class="admin-table-action-block">
                        <a href="' . url('movie/detail', $movies->slug) . '" data-toggle="tooltip" data-original-title="Page Preview" target="_blank" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>';
                } else {
                    $btn = ' <div class="admin-table-action-block">
                        <a style="cursor: not-allowed" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>';
                }
                $btn .= '<a href="' . route('movies.link', $movies->id) . '" data-toggle="tooltip" data-original-title="links" class="btn-success btn-floating"><i class="material-icons">link</i></a>
                        <a href="' . route('movies.edit', $movies->id) . '" data-toggle="tooltip" data-original-title="' . __('adminstaticwords.Edit') . '" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a><button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#deleteModal' . $movies->id . '"><i class="material-icons">delete</i> </button></div>';

                $btn .= '<div id="deleteModal' . $movies->id . '" class="delete-modal modal fade" role="dialog">
                      <div class="modal-dialog modal-sm">
                      <!-- Modal content-->
                      <div class="modal-content">
                      <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <div class="delete-icon"></div>
                      </div>
                      <div class="modal-body text-center">
                      <h4 class="modal-heading">' . __('adminstaticwords.AreYouSure') . '</h4>
                      <p>' . __('adminstaticwords.DeleteWarrning') . '</p>
                      </div>
                      <div class="modal-footer">
                      <form method="POST" action="' . route("movies.destroy", $movies->id) . '">
                      ' . method_field("DELETE") . '
                      ' . csrf_field() . '
                      <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">' . __('adminstaticwords.No') . '</button>
                      <button type="submit" class="btn btn-danger">' . __('adminstaticwords.Yes') . '</button>
                      </form>
                      </div>
                      </div>
                      </div>
                      </div>';

                return $btn;
            })->rawColumns(['checkbox', 'rating', 'thumbnail', 'tmdb', 'rating', 'addedby', 'status', 'action'])
                ->make(true);
        }

        return view('admin.movie.addedindex', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $menus = Menu::all();

        $director_ls = Director::pluck('name', 'id')->all();
        $actor_ls = Actor::pluck('name', 'id')->all();
        $genre_ls = Genre::pluck('name', 'id')->all();
        $a_lans = AudioLanguage::pluck('language', 'id')->all();
        $labels = Label::select('id', 'name')->get();
        $countries = Allcountry::get();

        $all_movies = Movie::all();
        $series_list = MovieSeries::all();
        $movie_list_exc_series = collect();
        $movie_list_with_only_series = collect();
        if (count($series_list) > 0) {
            foreach ($series_list as $item) {
                $series = Movie::where('id', $item->series_movie_id)
                    ->first();
                $movie_list_with_only_series->push($series);
            }
            $movie_list_exc_series = $all_movies->toBase()
                ->diff($movie_list_with_only_series->toBase());
            $movie_list_exc_series = $movie_list_exc_series->flatten()
                ->pluck('title', 'id');
            $movie_list_exc_series = json_decode($movie_list_exc_series, true);
        } else {
            $movie_list_exc_series = Movie::pluck('title', 'id')->all();
        }

        return view('admin.movie.create', compact('menus', 'director_ls', 'a_lans', 'director_ls', 'actor_ls', 'countries', 'genre_ls', 'movie_list_exc_series', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        ini_set('max_execution_time', 120);

        if (isset($request->movie_by_id)) {
            $request->validate(['title' => 'required']);
        } else {
            $request->validate(['title2' => 'required'], ['title2.required' => 'Movie ID is required !']);
        }

        $menus = null;

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
        }

        $input = $request->except('a_language', 'subtitle_list', 'movie_id');

        if (isset($request['is_protect'])) {
            $request->validate([
                'password' => 'required',
            ]);

            $input['is_protect'] = 1;
        } else {
            $input['is_protect'] = 0;
        }

        if (isset($request['is_upcoming'])) {

            $input['is_upcoming'] = 1;
        } else {
            $input['is_upcoming'] = 0;
        }

        $TMDB_API_KEY = env('TMDB_API_KEY');

        $a_lans = $request->input('a_language');

        if ($a_lans) {
            $a_lans = implode(',', $a_lans);
            $input['a_language'] = $a_lans;
        } else {
            $input['a_language'] = null;
        }

        if ($input['tmdb'] != 'Y') {
            $request->validate([
                'genre_id' => 'required',
            ]);
        }

        $input['created_by'] = Auth::user()->id;

        if (Auth::user()->is_assistant == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $input['status'] = $status;

        if (isset($request->subtitle)) {
            $subtitle = 1;
        } else {
            $subtitle = 0;
        }

        if (!isset($input['featured'])) {
            $input['featured'] = 0;
        }

        if (isset($input['is_kids'])) {
            $input['is_kids'] = 1;
        }else{
            $input['is_kids'] = 0;
        }
       
        if (!isset($input['series'])) {
            $input['series'] = 0;
        }
        if (isset($request->series)) {
            $request->validate([
                'movie_id' => 'required',
            ],
                [
                    'movie_id.required' => __('Forget to select movie of series'),
                ]);
        }
        if (isset($request->is_custom_label)) {
            $request->validate([
                'label_id' => 'required',
            ],
                [
                    'label_id.required' => __('Forget to select label'),
                ]);

            $input['label_id'] = $request->label_id;
            $input['is_custom_label'] = 1;
        } else {
            $input['is_custom_label'] = 0;
            $input['label_id'] = null;
        }

       
       
       if($request->is_kids != 1){
        $request->validate([
            'menu' => 'required'
        ],[
            'menu.required' => 'Please select atleast one menu'
        ]);
       }


        if ($input['tmdb'] == 'Y') {
            if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
                return back()->with('deleted', __('Please provide your TMDB api key or add movie by custom fields'));
            }

            $title = urlencode($input['title']);
            if (isset($request->movie_by_id)) {
                $search_data = @file_get_contents('https://api.themoviedb.org/3/search/movie?api_key=' . $TMDB_API_KEY . '&query=' . $title);

                if ($search_data) {
                    $data = json_decode($search_data, true);
                }

                $input['fetch_by'] = "title";

            } else {
                $title2 = urlencode($request->title2);
                $search_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $title2 . '?api_key=' . $TMDB_API_KEY);

                $x2 = json_decode($search_data, true);
                $data2 = [];
                $data2[] = ['results' => [$x2]];
                $data = $data2[0];

                $input['title'] = $data['results'][0]['title'];

                $input['fetch_by'] = "byID";
            }

            if (isset($data) && $data['results'] == null) {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }

            if (Session::has('changed_language')) {
                $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY . '&language=' . Session::get('changed_language'));
                $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
            } else {
                $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
                $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
            }

            if (!$fetch_movie && !$fetch_movie_for_genres) {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }

            $tmdb_movie = json_decode($fetch_movie, true);

            // Only for genres
            $tmdb_movie_for_genres = json_decode($fetch_movie_for_genres, true);

            if ($tmdb_movie != null) {
                $input['tmdb_id'] = $tmdb_movie['id'];
            } else {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }
            //Trailer
            if (!isset($input['trailer_url']) && $tmdb_movie != null && $TMDB_API_KEY != null) {

                if ($this->get_http_response_code('https://api.themoviedb.org/3/movie/' . $input['tmdb_id'] . '/videos?api_key=' . $TMDB_API_KEY) != "200") {

                    $input['trailer_url'] = null;
                } else {
                    $tmdb_trailers = @file_get_contents('https://api.themoviedb.org/3/movie/' . $input['tmdb_id'] . '/videos?api_key=' . $TMDB_API_KEY);
                    if ($tmdb_trailers) {
                        $tmdb_trailers = json_decode($tmdb_trailers, true);
                        if (isset($tmdb_trailers) && count($tmdb_trailers['results']) > 0) {
                            $input['trailer_url'] = 'https://youtu.be/' . $tmdb_trailers['results'][0]['key'];
                        }
                    } else {
                        $input['trailer_url'] = null;
                    }
                }
            }

            $thumbnail = null;
            $poster = null;
            //only thumbnail
            if ($file = $request->file('thumbnail')) {
                $validator = Validator::make(
                    [
                        'thumbnail' => $request->thumbnail,
                        'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                    ],
                    [
                        'thumbnail' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                    $img = Image::make($file->path());

                   
                    $img->save(public_path('/images/movies/thumbnails') . '/' . $thumbnail);

                }

            } else {
                $url = $tmdb_movie['poster_path'];
                $contents = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $url);
                $name = substr($url, strrpos($url, '/') + 1);
                $name = 'tmdb_' . $name;
                if ($contents) {
                    $tmdb_img = Storage::disk('imdb_poster_movie')->put($name, $contents);
                    if ($tmdb_img) {
                        $thumbnail = $name;
                    }
                }
            }

            //only poster
            if ($file = $request->file('poster')) {
                $validator = Validator::make(
                    [
                        'poster' => $request->poster,
                        'extension' => strtolower($request->poster->getClientOriginalExtension()),
                    ],
                    [
                        'poster' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $poster = 'poster_' . time() . $file->getClientOriginalName();
                    $img = Image::make($file->path());

                    $img->save(public_path('/images/movies/posters') . '/' . $poster);

                }
            } else {
                $url_2 = $tmdb_movie['backdrop_path'];
                $contents_2 = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $url_2);
                $name_2 = substr($url_2, strrpos($url_2, '/') + 1);

                $name_2 = 'poster_' . $name_2;

                if ($contents_2) {
                    $tmdb_img_2 = Storage::disk('imdb_backdrop_movie')->put($name_2, $contents_2);
                    if ($tmdb_img_2) {
                        $poster = $name_2;

                    }
                }
            }

            // Get Directors and create theme
            $tmdb_directors_id = collect();
            $get_tmdb_director_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_movie['id'] . '/credits?api_key=' . $TMDB_API_KEY);
            if ($get_tmdb_director_data) {
                $get_tmdb_director_data = json_decode($get_tmdb_director_data, true);
                $get_tmdb_director_data = (object) $get_tmdb_director_data;

                foreach ($get_tmdb_director_data->crew as $key => $item_dir) {

                    if ($item_dir['department'] == 'Directing') {
                        // getting director biography
                        $director_bio = null;
                        $director_birth = null;
                        $director_dob = null;
                        // getting actor id
                        $get_tmdb_director_biography = @file_get_contents('https://api.themoviedb.org/3/person/' . $item_dir['id'] . '?api_key=' . $TMDB_API_KEY);

                        if (isset($get_tmdb_director_biography)) {
                            $get_tmdb_director_biography = json_decode($get_tmdb_director_biography, true);

                            $director_bio = $get_tmdb_director_biography['biography'];
                            $director_birth = $get_tmdb_director_biography['place_of_birth'];
                            $director_dob = $get_tmdb_director_biography['birthday'];

                        }
                        $check_list = Director::where('name', $item_dir['name'])->first();
                        if (!isset($check_list)) {
                            // Director Image
                            $director_image = null;
                            $dir_image_url = $item_dir['profile_path'];
                            $dir_contents = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $dir_image_url);
                            $dir_img_name = substr($dir_image_url, strrpos($dir_image_url, '/') + 1);
                            $dir_img_name = 'tmdb_' . $dir_img_name;
                            if ($dir_contents) {
                                $dir_created_img = Storage::disk('director_image_path')->put($dir_img_name, $dir_contents);
                                if ($dir_created_img) {
                                    $director_image = $dir_img_name;
                                }
                            }

                            $tmdb_director = Director::updateOrCreate(['name' => $item_dir['name'], 'image' => $director_image, 'biography' => $director_bio, 'place_of_birth' => $director_birth, 'DOB' => $director_dob, 'slug' => str_slug($item_dir['name'], '-')]);

                            if (isset($tmdb_director)) {
                                $tmdb_directors_id->push($tmdb_director->id);
                            }

                        } else {
                            $tmdb_directors_id->push($check_list->id);
                        }
                    }

                }
            }

            $tmdb_directors_id = $tmdb_directors_id->flatten();

            // get actors and create theme
            $tmdb_actors_id = collect();
            $get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_movie['id'] . '/credits?api_key=' . $TMDB_API_KEY);
            if ($get_tmdb_actors_data) {
                $get_tmdb_actors_data = json_decode($get_tmdb_actors_data, true);
                $get_tmdb_actors_data = (object) $get_tmdb_actors_data;
                if (count([$get_tmdb_actors_data]) > 0) {
                    foreach ($get_tmdb_actors_data->cast as $key => $item_act) {
                        if ($key <= 4) {
                            $actor_bio = null;
                            $actor_birth = null;
                            $actor_dob = null;
                            // getting actor id
                            $get_tmdb_actors_biography = @file_get_contents('https://api.themoviedb.org/3/person/' . $item_act['id'] . '?api_key=' . $TMDB_API_KEY);
                            if (isset($get_tmdb_actors_biography)) {
                                $get_tmdb_actors_biography = json_decode($get_tmdb_actors_biography, true);

                                $actor_bio = $get_tmdb_actors_biography['biography'];
                                $actor_birth = $get_tmdb_actors_biography['place_of_birth'];
                                $actor_dob = $get_tmdb_actors_biography['birthday'];

                            }
                            $check_list = Actor::where('name', $item_act['name'])->first();

                            // if actor is not present already in our database
                            if (!isset($check_list)) {
                                // Actor Image
                                $actor_image = null;
                                $act_image_url = $item_act['profile_path'];
                                $act_contents = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $act_image_url);
                                $act_img_name = substr($act_image_url, strrpos($act_image_url, '/') + 1);
                                $act_img_name = 'tmdb_' . $act_img_name;
                                if ($act_contents) {
                                    $dir_created_img = Storage::disk('actor_image_path')->put($act_img_name, $act_contents);
                                    if ($dir_created_img) {
                                        $actor_image = $act_img_name;
                                    }
                                }
                                $tmdb_actor = Actor::updateOrCreate(['name' => $item_act['name'], 'image' => $actor_image, 'biography' => $actor_bio, 'place_of_birth' => $actor_birth, 'DOB' => $actor_dob, 'slug' => str_slug($item_act['name'], '-')]);

                                if (isset($tmdb_actor)) {
                                    $tmdb_actors_id->push($tmdb_actor->id);
                                }

                            } else {
                                $tmdb_actors_id->push($check_list->id);
                            }
                        }
                    }
                }
            }

            $tmdb_actors_id = $tmdb_actors_id->flatten();

            // get Genres and create theme
            $tmdb_genres_id = collect();

            if (isset($tmdb_movie_for_genres) && $tmdb_movie_for_genres != null) {
                foreach ($tmdb_movie_for_genres['genres'] as $tmdb_genre) {
                    $tmdb_genre1 = $tmdb_genre['name'];
                    $check_list = Genre::where('name', 'LIKE', "%$tmdb_genre1%")->first();

                    if (!isset($check_list)) {
                        $created_genre = Genre::create(['name' => ['en' => $tmdb_genre['name']], 'position' => (Genre::count() + 1)]);
                        $tmdb_genres_id->push($created_genre->id);
                    } else {
                        $tmdb_genres_id->push($check_list->id);
                    }
                }
            }
            $tmdb_genres_id = $tmdb_genres_id->flatten();

            if ($tmdb_movie['release_date'] != '') {
                $publish_year = substr($tmdb_movie['release_date'], 0, 4);
            } else {
                $publish_year = null;
            }

            $tmdb_directors_id = substr($tmdb_directors_id, 1, -1);
            $tmdb_actors_id = substr($tmdb_actors_id, 1, -1);
            $tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

            $keyword = $request->keyword;
            $description = $request->description;

        } else {
            //custom director
            $director_ids = $request->input('director_id');
            if ($director_ids) {
                $director_ids = implode(',', $director_ids);
                $tmdb_directors_id = $director_ids;
            } else {
                $tmdb_directors_id = null;
            }
            //custom actor
            $actor_ids = $request->input('actor_id');
            if ($actor_ids) {
                $actor_ids = implode(',', $actor_ids);
                $tmdb_actors_id = $actor_ids;
            } else {
                $tmdb_actors_id = null;
            }
            //custom genre
            $genre_ids = $request->input('genre_id');
            if ($genre_ids) {
                $genre_ids = implode(',', $genre_ids);
                $tmdb_genres_id = $genre_ids;
            } else {
                $tmdb_genres_id = null;
            }

            if ($file = $request->file('thumbnail')) {
                $validator = Validator::make(
                    [
                        'thumbnail' => $request->thumbnail,
                        'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                    ],
                    [
                        'thumbnail' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                    $img = Image::make($file->path());

                   
                    $img->save(public_path('/images/movies/thumbnails') . '/' . $thumbnail);

                }

            } else {
                $thumbnail = null;
            }

            if ($file = $request->file('poster')) {

                $validator = Validator::make(
                    [
                        'poster' => $request->poster,
                        'extension' => strtolower($request->poster->getClientOriginalExtension()),
                    ],
                    [
                        'poster' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $poster = 'poster_' . time() . $file->getClientOriginalName();
                    $img = Image::make($file->path());

                    
                    $img->save(public_path('/images/movies/posters') . '/' . $poster);

                }

            } else {
                $poster = null;
            }

            $tmdb_movie['runtime'] = $request->duration;
            $tmdb_movie['id'] = $request->tmdb_id;
            $tmdb_movie['overview'] = $request->detail;
            $tmdb_movie['vote_average'] = $request->rating;
            $publish_year = $request->publish_year;
            $tmdb_movie['release_date'] = $request->released;
            $keyword = $request->keyword;
            $description = $request->description;

            if (isset($request->movie_by_id)) {
                $input['fetch_by'] = 'title';
            } else {
                $input['fetch_by'] = 'byID';
            }

        }

        if ($request->slug != null) {
            $input['slug'] = $request->slug;
        } else {
            $slug = str_slug($request['title'], '-');
            $input['slug'] = $slug;
        }
        $country = 0;
            if(isset($input['country'])){
                $country =  $input['country'];
            }

        try {

            $created_movie = Movie::create([
                'title' => $input['title'],
                'keyword' => $keyword,
                'description' => $description,
                'tmdb_id' => $tmdb_movie['id'],
                'duration' => $tmdb_movie['runtime'],
                'tmdb' => $input['tmdb'],
                'director_id' => $tmdb_directors_id,
                'actor_id' => $tmdb_actors_id,
                'genre_id' => $tmdb_genres_id,
                'trailer_url' => $input['trailer_url'],
                'subtitle' => $subtitle,
                'featured' => $input['featured'],
                'series' => $input['series'],
                'detail' => $tmdb_movie['overview'],
                'rating' => $tmdb_movie['vote_average'],
                'publish_year' => $publish_year ? $publish_year : null,
                'released' => $tmdb_movie['release_date'],
                'maturity_rating' => $input['maturity_rating'],
                'a_language' => $input['a_language'],
                'thumbnail' => $thumbnail ? $thumbnail : null,
                'poster' => $poster ? $poster : null,
                'fetch_by' => $input['fetch_by'],
                'created_by' => Auth::user()->id,
                'status' => $status ? $status : 0,
                'is_protect' => $input['is_protect'],
                'password' => $input['password'] != null ? Crypt::encrypt($input['password']) : null,
                'slug' => $input['slug'],
                'is_upcoming' => $input['is_upcoming'],
                'upcoming_date' => isset($input['upcoming_date']) && $input['upcoming_date'] != null ? $input['upcoming_date'] : null,
                'is_custom_label' => $input['is_custom_label'],
                'label_id' => $input['label_id'],
                'is_kids'=> $input['is_kids'],
                'country'=> $country,
            ]);

        } catch (\Exception $e) {

            return back()->with('deleted', $e->getMessage());
        }

        try {
            //subtitle add
            if (isset($request->subtitle)) {

                if ($request->has('sub_t')) {
                    $validator = Validator::make(
                        [
                            'sub_t' => $request->sub_t,
                        ],
                        [
                            'sub_t' => 'required',
                        ]
                    );
                    if ($validator->fails()) {
                        return back()->with('deleted', __('Invalid file format Please use txt and vtt file format !'))->withInput();
                    } else {
                        foreach ($request->file('sub_t') as $key => $image) {

                            $name = 'movie_subtitle_' . time() . $image->getClientOriginalName();
                            $image->move(public_path() . '/subtitles/', $name);

                            $form = new Subtitles();
                            $form->sub_lang = $request->sub_lang[$key];
                            $form->sub_t = $name;
                            $form->m_t_id = $created_movie->id;
                            $form->save();
                        }
                    }
                }

            }

            if ($input['series'] == 1) {
                MovieSeries::create(['movie_id' => $request->movie_id, 'series_movie_id' => $created_movie->id]);
            }

            if ($request->selecturl == "iframeurl") {

                VideoLink::create(['movie_id' => $created_movie->id, 'type' => 'iframeurl', 'iframeurl' => isset($input['iframeurl']) ? $input['iframeurl'] : null, 'ready_url' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'upload_video' => null]);

            } else if ($request->selecturl == "youtubeurl" || $request->selecturl == "vimeourl" || $request->selecturl == "customurl" || $request->selecturl == "vimeoapi" || $request->selecturl == "youtubeapi") {

                VideoLink::create(['movie_id' => $created_movie->id, 'type' => 'readyurl', 'ready_url' => isset($input['ready_url']) ? $input['ready_url'] : null, 'iframeurl' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'upload_video' => null]);

            } elseif ($request->selecturl == "uploadvideo") {
                $aws = 0;

                if ($request->upload_video != null) {

                    if ($request->upload_aws == 'on') {
                        $aws = 1;
                        $videoname = time() . $file->getClientOriginalName();

                        // aws storage

                        $t = Storage::disk('s3')->put($videoname, file_get_contents($file), 'public');
                        $file->move('movies_upload/', $videoname);
                        $upload_video = 'https://' . env('bucket') . '.s3.' . env('region') . '.amazonaws.com/movies_upload' . $videoname;

                        $videoname = Storage::disk('s3')->url($videoname);

                    } else {
                        if (strstr($request->upload_video, '.mp4') || strstr($request->upload_video, '.m3u8')) {

                            $upload_video = url('movies_upload/' . $request->upload_video);

                        } else {
                            return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                        }

                    }

                    VideoLink::create(['movie_id' => $created_movie->id, 'upload_video' => $upload_video, 'type' => 'upload_video', 'ready_url' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null]);
                    $videlin = Videolink::where('upload_video', $request->upload_video)->first();
                    session()
                        ->put('last_movie', ['aws' => $aws, 'movie_id' => $created_movie->id,

                        ]);
                }

            } elseif ($request->selecturl == 'multiqcustom') {

                if ($request->upload_video_360 != null) {
                    if (strstr($request->upload_video_360, '.mp4') || strstr($request->upload_video_360, '.m3u8')) {

                        $url_360 = url('movies_upload/url_360/' . $request->upload_video_360);

                    } else {
                        return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                    }

                } else {
                    $url_360 = $request->url_360;
                }

                if ($request->upload_video_480 != null) {
                    if (strstr($request->upload_video_480, '.mp4') || strstr($request->upload_video_480, '.m3u8')) {

                        $url_480 = url('movies_upload/url_480/' . $request->upload_video_480);

                    } else {
                        return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                    }

                } else {
                    $url_480 = $request->url_480;
                }

                if ($file = $request->file('upload_video_720')) {
                    if (strstr($request->upload_video_720, '.mp4') || strstr($request->upload_video_720, '.m3u8')) {

                        $url_720 = url('movies_upload/url_720/' . $request->upload_video_720);

                    } else {
                        return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                    }

                } else {
                    $url_720 = $request->url_720;
                }

                if ($request->upload_video_1080 != null) {
                    if (strstr($request->upload_video_1080, '.mp4') || strstr($request->upload_video_1080, '.m3u8')) {

                        $url_1080 = url('movies_upload/url_1080/' . $request->upload_video_1080);

                    } else {
                        return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                    }

                } else {
                    $url_1080 = $request->url_1080;
                }

                VideoLink::create(['movie_id' => $created_movie->id, 'type' => 'multiquality', 'url_360' => $url_360, 'url_480' => $url_480, 'url_720' => $url_720, 'url_1080' => $url_1080, 'upload_video' => null]);

            }

            if ($menus != null) {
                if (count($menus) > 0) {
                    foreach ($menus as $key => $value) {
                        MenuVideo::create(['menu_id' => $value, 'movie_id' => $created_movie->id]);
                    }
                }
            }

            return back()->with('added', __('Movie has been added'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $url
     * @return \Illuminate\Http\Response
     */
    public function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menus = Menu::all();
        $director_ls = Director::all();
        $actor_ls = Actor::all();
        $genre_ls = Genre::all();
        $all_languages = AudioLanguage::all();
        $movie = Movie::find($id);
        $labels = Label::select('id', 'name')->get();

        $all_movies = Movie::all();
        $series_list = MovieSeries::all();
        $movie_list_exc_series = collect();
        $movie_list_with_only_series = collect();
        if (count($series_list) > 0) {
            foreach ($series_list as $item) {
                $series = Movie::where('id', $item->series_movie_id)
                    ->first();
                $movie_list_with_only_series->push($series);
            }

            $movie_list_exc_series = $all_movies->toBase()
                ->diff($movie_list_with_only_series->toBase());
            $movie_list_exc_series = $movie_list_exc_series->flatten()
                ->pluck('title', 'id');
            $movie_list_exc_series = json_decode($movie_list_exc_series, true);

        } else {
            $movie_list_exc_series = Movie::pluck('title', 'id')->all();
        }
        // get old audio language values
        $old_lans = collect();
        $a_lans = collect();
        if ($movie->a_language != null) {
            $old_list = explode(',', $movie->a_language);
            for ($i = 0; $i < count($old_list); $i++) {
                $old = AudioLanguage::find(trim($old_list[$i]));
                if (isset($old)) {
                    $old_lans->push($old);
                }
            }
        }
        $a_lans = $a_lans->filter(function ($value, $key) {
            return $value != null;
        });
        $a_lans = $all_languages->diff($old_lans);

        // get old subtitle language values
        $old_subtitles = collect();
        $a_subs = collect();
        if ($movie->subtitle == 1) {
            if ($movie->subtitle_list != null) {
                $old_list = explode(',', $movie->subtitle_list);
                for ($i = 0; $i < count($old_list); $i++) {
                    $old2 = AudioLanguage::find(trim($old_list[$i]));
                    if (isset($old2)) {
                        $old_subtitles->push($old2);
                    }
                }
            }
        }
        $a_subs = $a_subs->filter(function ($value, $key) {
            return $value != null;
        });
        $a_subs = $all_languages->diff($old_subtitles);

        // get old director list
        $old_director = collect();
        if ($movie->director_id != null) {
            $old_list = explode(',', $movie->director_id);
            for ($i = 0; $i < count($old_list); $i++) {
                $old3 = Director::find(trim($old_list[$i]));
                if (isset($old3)) {
                    $old_director->push($old3);
                }
            }
        }
        $director_ls = $director_ls->filter(function ($value, $key) {
            return $value != null;
        });
        $director_ls = $director_ls->diff($old_director);

        // get old actor list
        $old_actor = collect();
        if ($movie->actor_id != null) {
            $old_list = explode(',', $movie->actor_id);
            for ($i = 0; $i < count($old_list); $i++) {
                $old4 = Actor::find(trim($old_list[$i]));
                if (isset($old4)) {
                    $old_actor->push($old4);
                }
            }
        }
        $old_actor = $old_actor->filter(function ($value, $key) {
            return $value != null;
        });
        $actor_ls = $actor_ls->diff($old_actor);

        // get old genre list
        $old_genre = collect();
        if ($movie->genre_id != null) {
            $old_list = explode(',', $movie->genre_id);
            for ($i = 0; $i < count($old_list); $i++) {
                $old5 = Genre::find(trim($old_list[$i]));
                if (isset($old5)) {
                    $old_genre->push($old5);
                }
            }
        }
        $genre_ls = $genre_ls->filter(function ($value, $key) {
            return $value != null;
        });

        $genre_ls = $genre_ls->diff($old_genre);

        $this_movie_series = MovieSeries::where('series_movie_id', $id)->get();
        if (count($this_movie_series) > 0) {
            $this_movie_series_detail = Movie::where('id', $this_movie_series[0]->movie_id)
                ->get();
        }

        $video_link = Videolink::where('movie_id', $id)->first();
        $countries = Allcountry::get(); 

        return view('admin.movie.edit', compact('movie', 'director_ls', 'actor_ls', 'genre_ls', 'movie_list_exc_series', 'a_lans', 'old_lans', 'countries', 'a_subs', 'video_link', 'old_subtitles', 'old_director', 'old_actor', 'old_genre', 'menus', 'labels'));
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
       
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        if (isset($request->series)) {
            $request->validate([
                'movie_id' => 'required',
            ],
                [
                    'movie_id.required' => __('Forget to select movie'),
                ]);
        }
        $movie = Movie::findOrFail($id);

        if (isset($request->subtitle)) {
            $subtitle = 1; //for custom

            if ($request->has('sub_t')) {
                $validator = Validator::make(
                    [
                        'sub_t' => $request->sub_t,
                    ],
                    [
                        'sub_t' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use txt and vtt file format !'))->withInput();
                } else {
                    foreach ($request->file('sub_t') as $key => $image) {

                        $name = 'movie_subtitle_' . time() . $image->getClientOriginalName();
                        $image->move(public_path() . '/subtitles/', $name);

                        $form = new Subtitles();
                        $form->sub_lang = $request->sub_lang[$key];
                        $form->sub_t = $name;
                        $form->m_t_id = $movie->id;
                        $form->save();
                    }
                }
            }
        } else {
            $subtitle = 0;

        }

        $menus = null;

        if (isset($request->menu) && count($request->menu) > 0) {
            $menus = $request->menu;
        }

        $input = $request->except('a_language', 'director_id', 'actor_id', 'genre_id', 'subtitle_list', 'movie_id');

        $TMDB_API_KEY = env('TMDB_API_KEY');

        $a_lans = $request->input('a_language');
        if ($a_lans) {
            $a_lans = implode(',', $a_lans);
            $input['a_language'] = $a_lans;
        } else {
            $input['a_language'] = null;
        }

        if ($input['tmdb'] != 'Y') {
            $request->validate(['genre_id' => 'required']);
        }

        if (!isset($input['featured'])) {
            $input['featured'] = 0;
        }
        if (!isset($input['series'])) {
            $input['series'] = 0;
        }

        if (isset($request['is_protect'])) {
            $input['is_protect'] = 1;
        } else {
            $input['is_protect'] = 0;
        }

        if ($input['is_protect'] == 1) {
            $request->validate([
                'password' => 'required',
            ]);
        }
        if ($request->slug != null) {
            $input['slug'] = $request->slug;
        } else {
            $slug = str_slug($input['title'], '-');
            $input['slug'] = $slug;
        }

        if (isset($request->subtitle)) {
            $subtitle = 1;
        } else {

            $subtitle = 0;
        }

        if (isset($request['is_upcoming'])) {

            $input['is_upcoming'] = 1;
        } else {
            $input['is_upcoming'] = 0;
        }

        if($request->is_kids != 1){
            $request->validate([
                'menu' => 'required'
            ],[
                'menu.required' => 'Please select atleast one menu'
            ]);
           }

        if ($input['tmdb'] == 'Y') {

            if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
                return back()->with('deleted', __('Please provide your TMDB api key or add movie by custom fields'));
            }

            $title = urlencode($input['title']);

            if (isset($request->movie_by_id)) {
                $search_data = @file_get_contents('https://api.themoviedb.org/3/search/movie?api_key=' . $TMDB_API_KEY . '&query=' . $title);

                if ($search_data) {
                    $data = json_decode($search_data, true);
                }

                $input['fetch_by'] = "title";

            } else {
                $title2 = urlencode($request->title2);
                $search_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $title2 . '?api_key=' . $TMDB_API_KEY);

                $x2 = json_decode($search_data, true);
                $data2 = [];
                $data2[] = ['results' => [$x2]];
                $data = $data2[0];

                $input['title'] = $data['results'][0]['title'];

                $input['fetch_by'] = "byID";
            }

            if (isset($data) && $data['results'] == null) {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }

            if (Session::has('changed_language')) {
                $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY . '&language=' . Session::get('changed_language'));
                $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
            } else {
                $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
                $fetch_movie_for_genres = @file_get_contents('https://api.themoviedb.org/3/movie/' . $data['results'][0]['id'] . '?api_key=' . $TMDB_API_KEY);
            }

            if (!$fetch_movie && !$fetch_movie_for_genres) {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }

            $tmdb_movie = json_decode($fetch_movie, true);
            // Only for genres
            $tmdb_movie_for_genres = json_decode($fetch_movie_for_genres, true);

            if ($tmdb_movie != null) {
                $input['tmdb_id'] = $tmdb_movie['id'];
            } else {
                return back()->with('deleted', __('Movie does not found by tmdb servers !'));
            }

            if (!isset($input['trailer_url']) && $tmdb_movie != null && $TMDB_API_KEY != null) {
                if ($this->get_http_response_code('https://api.themoviedb.org/3/movie/' . $input['tmdb_id'] . '/videos?api_key=' . $TMDB_API_KEY) != "200") {
                    $input['trailer_url'] = null;
                } else {
                    $tmdb_trailers = @file_get_contents('https://api.themoviedb.org/3/movie/' . $input['tmdb_id'] . '/videos?api_key=' . $TMDB_API_KEY);
                    if ($tmdb_trailers) {
                        $tmdb_trailers = json_decode($tmdb_trailers, true);
                        if ($tmdb_trailers['results'] != null) {
                            $input['trailer_url'] = 'https://youtu.be/' . $tmdb_trailers['results'][0]['key'];
                        }
                    } else {
                        $input['trailer_url'] = null;
                    }
                }
            }

            $thumbnail = null;
            $poster = null;

            if ($file = $request->file('thumbnail')) {
                $validator = Validator::make(
                    [
                        'thumbnail' => $request->thumbnail,
                        'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                    ],
                    [
                        'thumbnail' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {

                    $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                    if ($movie->thumbnail != null) {
                        $content = @file_get_contents(public_path() . '/images/movies/thumbnails/' . $movie->thumbnail);
                        if ($content) {
                            unlink(public_path() . "/images/movies/thumbnails/" . $movie->thumbnail);
                        }
                    }
                    $img = Image::make($file->path());

                    
                    $img->save(public_path('/images/movies/thumbnails') . '/' . $thumbnail);

                }
            } else {

                $url = $tmdb_movie['poster_path'];
                $contents = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $url);
                $name = substr($url, strrpos($url, '/') + 1);
                $name = 'tmdb_' . $name;
                if ($contents) {
                    $tmdb_img = Storage::disk('imdb_poster_movie')->put($name, $contents);
                    if ($tmdb_img) {
                        $thumbnail = $name;
                    }
                }
            }

            if ($file = $request->file('poster')) {
                $validator = Validator::make(
                    [
                        'poster' => $request->poster,
                        'extension' => strtolower($request->poster->getClientOriginalExtension()),
                    ],
                    [
                        'poster' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {

                    $poster = 'poster_' . time() . $file->getClientOriginalName();
                    if ($movie->poster != null) {
                        $content = @file_get_contents(public_path() . '/images/movies/posters/' . $movie->poster);
                        if ($content) {
                            unlink(public_path() . "/images/movies/posters/" . $movie->poster);
                        }
                    }
                    $img = Image::make($file->path());

                    
                    $img->save(public_path('/images/movies/posters') . '/' . $poster);

                }
            } else {

                $url_2 = $tmdb_movie['backdrop_path'];
                $contents_2 = @file_get_contents('https://image.tmdb.org/t/p/w300/' . $url_2);
                $name_2 = substr($url_2, strrpos($url_2, '/') + 1);
                $name_2 = 'poster_' . $name_2;
                if ($contents_2) {
                    $tmdb_img_2 = Storage::disk('imdb_backdrop_movie')->put($name_2, $contents_2);
                    if ($tmdb_img_2) {
                        $poster = $name_2;
                    }
                }
            }

            // Get Directors and create theme
            $tmdb_directors_id = collect();
            $get_tmdb_director_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_movie['id'] . '/credits?api_key=' . $TMDB_API_KEY);
            if ($get_tmdb_director_data) {
                $get_tmdb_director_data = json_decode($get_tmdb_director_data, true);
                $get_tmdb_director_data = (object) $get_tmdb_director_data;
                foreach ($get_tmdb_director_data->crew as $key => $item_dir) {

                    if ($item_dir['department'] === 'Directing') {
                        // getting director biography
                        $director_bio = null;
                        $director_birth = null;
                        $director_dob = null;
                        // getting Director id
                        $get_tmdb_director_biography = @file_get_contents('https://api.themoviedb.org/3/person/' . $item_dir['id'] . '?api_key=' . $TMDB_API_KEY);

                        if (isset($get_tmdb_director_biography)) {
                            $get_tmdb_director_biography = json_decode($get_tmdb_director_biography, true);

                            $director_bio = $get_tmdb_director_biography['biography'];
                            $director_birth = $get_tmdb_director_biography['place_of_birth'];
                            $director_dob = $get_tmdb_director_biography['birthday'];

                        }
                        $check_list = Director::where('name', $item_dir['name'])->first();

                        if (!isset($check_list)) {

                            // Director Image
                            $director_image = null;
                            $dir_image_url = $item_dir['profile_path'];
                            $dir_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/' . $dir_image_url);
                            $dir_img_name = substr($dir_image_url, strrpos($dir_image_url, '/') + 1);
                            $dir_img_name = 'tmdb_' . $dir_img_name;
                            if ($dir_contents) {
                                $dir_created_img = Storage::disk('director_image_path')->put($dir_img_name, $dir_contents);
                                if ($dir_created_img) {
                                    $director_image = $dir_img_name;
                                }
                            }

                            $tmdb_director = Director::updateOrCreate(['name' => $item_dir['name'], 'image' => $director_image, 'biography' => $director_bio, 'place_of_birth' => $director_birth, 'DOB' => $director_dob, 'slug' => str_slug($item_dir['name'], '-')]);

                            if (isset($tmdb_director)) {
                                $tmdb_directors_id->push($tmdb_director->id);
                            }

                        } else {
                            $tmdb_directors_id->push($check_list->id);
                        }
                    }

                }
            }
            $tmdb_directors_id = $tmdb_directors_id->flatten();

            // get actors and create theme
            $tmdb_actors_id = collect();
            $get_tmdb_actors_data = @file_get_contents('https://api.themoviedb.org/3/movie/' . $tmdb_movie['id'] . '/credits?api_key=' . $TMDB_API_KEY);
            if ($get_tmdb_actors_data) {
                $get_tmdb_actors_data = json_decode($get_tmdb_actors_data, true);

                if (count($get_tmdb_actors_data) > 0) {
                    foreach ($get_tmdb_actors_data['cast'] as $key => $item_act) {
                        if ($key <= 4) {
                            $actor_bio = null;
                            $actor_birth = null;
                            $actor_dob = null;
                            // getting actor id
                            $get_tmdb_actors_biography = @file_get_contents('https://api.themoviedb.org/3/person/' . $item_act['id'] . '?api_key=' . $TMDB_API_KEY);
                            if (isset($get_tmdb_actors_biography)) {
                                $get_tmdb_actors_biography = json_decode($get_tmdb_actors_biography, true);

                                $actor_bio = $get_tmdb_actors_biography['biography'];
                                $actor_birth = $get_tmdb_actors_biography['place_of_birth'];
                                $actor_dob = $get_tmdb_actors_biography['birthday'];

                            }

                            $check_list = Actor::where('name', $item_act['name'])->first();

                            if (!isset($check_list)) {

                                // Actor Image
                                $actor_image = null;
                                $act_image_url = $item_act['profile_path'];
                                $act_contents = @file_get_contents('https://image.tmdb.org/t/p/w500/' . $act_image_url);
                                $act_img_name = substr($act_image_url, strrpos($act_image_url, '/') + 1);
                                $act_img_name = 'tmdb_' . $act_img_name;
                                if ($act_contents) {
                                    $dir_created_img = Storage::disk('actor_image_path')->put($act_img_name, $act_contents);
                                    if ($dir_created_img) {
                                        $actor_image = $act_img_name;
                                    }
                                }

                                $tmdb_actor = Actor::updateOrCreate(['name' => $item_act['name'], 'image' => $actor_image, 'biography' => $actor_bio, 'place_of_birth' => $actor_birth, 'DOB' => $actor_dob, 'slug' => str_slug($item_act['name'], '-')]);

                                if (isset($tmdb_actor)) {
                                    $tmdb_actors_id->push($tmdb_actor->id);
                                }

                            } else {

                                $tmdb_actors_id->push($check_list->id);

                            }
                        }
                    }
                }
            }
            $tmdb_actors_id = $tmdb_actors_id->flatten();

            // get Genres and create theme
            $tmdb_genres_id = collect();
            if (isset($tmdb_movie_for_genres) && $tmdb_movie_for_genres != null) {
                foreach ($tmdb_movie_for_genres['genres'] as $tmdb_genre) {

                    $tmdb_genre1 = $tmdb_genre['name'];
                    $check_list = Genre::where('name', 'LIKE', "%$tmdb_genre1%")->first();

                    if (!isset($check_list)) {
                        $created_genre = Genre::create(['name' => ['en' => $tmdb_genre['name']], 'position' => (Genre::count() + 1)]);

                        $tmdb_genres_id->push($created_genre->id);
                    } else {
                        $tmdb_genres_id->push($check_list->id);
                    }
                }
            }
            $tmdb_genres_id = $tmdb_genres_id->flatten();

            if ($tmdb_movie['release_date'] != '') {
                $publish_year = substr($tmdb_movie['release_date'], 0, 4);
            } else {
                $publish_year = null;
            }

            $tmdb_directors_id = substr($tmdb_directors_id, 1, -1);
            $tmdb_actors_id = substr($tmdb_actors_id, 1, -1);
            $tmdb_genres_id = substr($tmdb_genres_id, 1, -1);

            $keyword = $request->keyword;
            $description = $request->description;

            if (isset($request->movie_by_id)) {
                $input['fetch_by'] = 'title';
            } else {
                $input['fetch_by'] = 'byID';
            }

        } else {

            if (isset($request->movie_by_id)) {
                $input['fetch_by'] = 'title';
            } else {
                $input['fetch_by'] = 'byID';
            }

            $director_ids = $request->input('director_id');
            if ($director_ids) {
                $director_ids = implode(',', $director_ids);
                $tmdb_directors_id = $director_ids;
            } else {
                $tmdb_directors_id = null;
            }

            $actor_ids = $request->input('actor_id');
            if ($actor_ids) {
                $actor_ids = implode(',', $actor_ids);
                $tmdb_actors_id = $actor_ids;
            } else {
                $tmdb_actors_id = null;
            }

            $genre_ids = $request->input('genre_id');
            if ($genre_ids) {
                $genre_ids = implode(',', $genre_ids);
                $tmdb_genres_id = $genre_ids;
            } else {
                $tmdb_genres_id = null;
            }

            if ($file = $request->file('thumbnail')) {
                $validator = Validator::make(
                    [
                        'thumbnail' => $request->thumbnail,
                        'extension' => strtolower($request->thumbnail->getClientOriginalExtension()),
                    ],
                    [
                        'thumbnail' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $thumbnail = 'thumb_' . time() . $file->getClientOriginalName();
                    if ($movie->thumbnail != null) {
                        $content = @file_get_contents(public_path() . '/images/movies/thumbnails/' . $movie->thumbnail);
                        if ($content) {
                            unlink(public_path() . "/images/movies/thumbnails/" . $movie->thumbnail);
                        }
                    }
                    $img = Image::make($file->path());

                    
                    $img->save(public_path('/images/movies/thumbnails') . '/' . $thumbnail);

                }

            } else {
                if ($movie->thumbnail == null) {
                    $thumbnail = null;
                } else {
                    $thumbnail = $movie->thumbnail;
                }
            }

            if ($file = $request->file('poster')) {
                $validator = Validator::make(
                    [
                        'poster' => $request->poster,
                        'extension' => strtolower($request->poster->getClientOriginalExtension()),
                    ],
                    [
                        'poster' => 'required',
                        'extension' => 'required|in:jpg,jpeg,png,webp',
                    ]
                );
                if ($validator->fails()) {
                    return back()->with('deleted', __('Invalid file format Please use jpg,jpeg and png image format !'))->withInput();
                } else {
                    $poster = 'poster_' . time() . $file->getClientOriginalName();
                    if ($movie->poster != null) {
                        $content = @file_get_contents(public_path() . '/images/movies/posters/' . $movie->poster);
                        if ($content) {
                            unlink(public_path() . "/images/movies/posters/" . $movie->poster);
                        }
                    }
                    $img = Image::make($file->path());

                    
                    $img->save(public_path('/images/movies/posters') . '/' . $poster);

                }

            } else {
                if ($movie->poster == null) {
                    $poster = null;
                } else {
                    $poster = $movie->poster;
                }
            }

            $tmdb_movie['runtime'] = $request->duration;
            $tmdb_movie['id'] = $request->tmdb_id;
            $tmdb_movie['overview'] = $request->detail;
            $tmdb_movie['vote_average'] = $request->rating;
            $publish_year = $request->publish_year;
            $tmdb_movie['release_date'] = $request->released;
            $keyword = $request->keyword;
            $description = $request->description;

        }

        if (isset($request->is_custom_label)) {
            $request->validate([
                'label_id' => 'required',
            ],
                [
                    'label_id.required' => __('Forget to select label'),
                ]);

            $input['label_id'] = $request->label_id;
            $input['is_custom_label'] = 1;
        } else {
            $input['is_custom_label'] = 0;
            $input['label_id'] = null;
        }

        try {

            if ($input['series'] == 1 && $movie->series == 1) {
                $movie_series = MovieSeries::where('series_movie_id', $movie->id);
                $movie_series->update(['movie_id' => $request->movie_id, 'series_movie_id' => $movie->id]);
            }

            if ($input['series'] == 1 && $movie->series != 1) {
                MovieSeries::create(['movie_id' => $request->movie_id, 'series_movie_id' => $movie->id]);
            }
            if (isset($input['is_kids'])) {
                $input['is_kids'] = 1;
            }else{
                $input['is_kids'] = 0;
            }
           
            $country = 0;
            if(isset($input['country'])){
                $country =  $input['country'];
            }

            $movie->update([
                'title' => $input['title'],
                'tmdb_id' => $tmdb_movie['id'],
                'keyword' => $keyword,
                'description' => $description,
                'duration' => $tmdb_movie['runtime'],
                'tmdb' => $input['tmdb'],
                'director_id' => $tmdb_directors_id,
                'actor_id' => $tmdb_actors_id,
                'genre_id' => $tmdb_genres_id,
                'trailer_url' => $input['trailer_url'],
                'subtitle' => $subtitle,
                'featured' => $input['featured'],
                'series' => $input['series'],
                'detail' => $tmdb_movie['overview'],
                'rating' => $tmdb_movie['vote_average'],
                'publish_year' => $publish_year,
                'released' => $tmdb_movie['release_date'],
                'maturity_rating' => $input['maturity_rating'],
                'a_language' => $input['a_language'],
                'thumbnail' => $thumbnail,
                'poster' => $poster,
                'fetch_by' => $input['fetch_by'],
                'is_protect' => $input['is_protect'],
                'password' => $input['password'] != null ? Crypt::encrypt($input['password']) : null,
                'slug' => $input['slug'],
                'is_upcoming' => $input['is_upcoming'],
                'upcoming_date' => isset($input['upcoming_date']) && $input['upcoming_date'] != null ? $input['upcoming_date'] : null,
                'is_custom_label' => $input['is_custom_label'],
                'label_id' => $input['label_id'],
                'is_kids' => $input['is_kids'],
                'country'=> $country,
            ]);

            if (isset($movie->video_link)) {

                if ($request->selecturl == "iframeurl") {

                    $movie->video_link->update(['iframeurl' => isset($input['iframeurl']) ? $input['iframeurl'] : null, 'type' => 'iframeurl', 'ready_url' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'upload_video' => null, 'upload_video' => null]);

                } else {

                    if ($request->selecturl == "youtubeurl" || $request->selecturl == "vimeourl" || $request->selecturl == "customurl" || $request->selecturl == "vimeoapi" || $request->selecturl == "youtubeapi") {

                        $movie->video_link->update(['type' => 'readyurl', 'iframeurl' => null, 'ready_url' => isset($input['ready_url']) ? $input['ready_url'] : null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'upload_video' => null, 'upload_video' => null]);

                    } elseif ($request->selecturl == "uploadvideo") {

                        // upload video code
                        $aws = 0;
                        if ($request->upload_video != null) {

                            if ($request->upload_aws == 'on') {
                                $aws = 1;
                                $videoname = time() . $file->getClientOriginalName();
                                // aws storage
                                $t = Storage::disk('s3')->put($videoname, file_get_contents($file), 'public');
                                $file->move('movies_upload/', $videoname);

                                $upload_video = 'https://' . env('bucket') . '.s3.' . env('region') . '.amazonaws.com/movies_upload' . $videoname;
                            } else {

                                if (strstr($request->upload_video, '.mp4') || strstr($request->upload_video, '.m3u8')) {

                                    $upload_video = url('movies_upload/' . $request->upload_video);

                                } else {
                                    return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                                }

                            }

                            $movie->video_link->update(['iframeurl' => null, 'ready_url' => null, 'upload_video' => $upload_video, 'type' => 'upload_video', 'ready_url' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null,
                            ]);
                            $videlin = Videolink::where('upload_video', $request->upload_video)->first();
                            session()->put('last_movie', ['aws' => $aws, 'movie_id' => $movie->id]);

                        }

                    } elseif ($request->selecturl == 'multiqcustom') {

                        $url = url('/movies_upload');

                        if ($request->upload_video_360 != null) {

                            if (strstr($request->upload_video_360, '.mp4') || strstr($request->upload_video_360, '.m3u8')) {

                                $url_360 = url('movies_upload/url_360/' . $request->upload_video_360);

                            } else {
                                return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                            }

                        } else {

                            if ($movie
                                ->video_link->url_360 != $request->url_360) {

                                $file_360 = trim($movie
                                        ->video_link->url_360, $url);

                                    if ($movie->video_link->url_360 != '') {
                                    if (file_exists('movies_upload/url_360/' . $file_360)) {
                                        $file_360 = trim($movie
                                                ->video_link->url_360, $url);
                                            unlink('movies_upload/url_360/' . $file_360);
                                    }
                                }

                                $url_360 = $request->url_360;

                            } else {
                                $url_360 = $request->url_360;
                            }

                        }

                        if ($request->upload_video_480 != null) {
                            if (strstr($request->upload_video_480, '.mp4') || strstr($request->upload_video_480, '.m3u8')) {

                                $url_480 = url('movies_upload/url_480/' . $request->upload_video_480);

                            } else {
                                return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                            }

                        } else {

                            if ($movie
                                ->video_link->url_480 != $request->url_480) {

                                $file_480 = trim($movie
                                        ->video_link->url_480, $url);

                                    if ($movie->video_link->url_480 != '') {
                                    if (file_exists('movies_upload/url_480/' . $file_480)) {
                                        $file_480 = trim($movie
                                                ->video_link->url_480, $url);
                                            unlink('movies_upload/url_480/' . $file_480);
                                    }
                                }

                                $url_480 = $request->url_480;

                            } else {
                                $url_480 = $request->url_480;
                            }

                        }

                        if ($request->upload_video_720 != null) {
                            if (strstr($request->upload_video_720, '.mp4') || strstr($request->upload_video_720, '.m3u8')) {

                                $url_720 = url('movies_upload/url_720/' . $request->upload_video_720);

                            } else {
                                return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                            }

                        } else {

                            if ($movie
                                ->video_link->url_720 != $request->url_720) {

                                $file_720 = trim($movie
                                        ->video_link->url_720, $url);

                                    if ($movie->video_link->url_720 != '') {
                                    if (file_exists('movies_upload/url_720/' . $file_720)) {
                                        $file_720 = trim($movie
                                                ->video_link->url_720, $url);
                                            unlink('movies_upload/url_720/' . $file_720);
                                    }
                                }

                                $url_720 = $request->url_720;

                            } else {
                                $url_720 = $request->url_720;
                            }

                        }

                        if ($request->upload_video_1080 != null) {
                            if (strstr($request->upload_video_1080, '.mp4') || strstr($request->upload_video_1080, '.m3u8')) {

                                $url_1080 = url('movies_upload/url_1080/' . $request->upload_video_1080);

                            } else {
                                return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                            }

                        } else {

                            if ($movie
                                ->video_link->url_1080 != $request->url_1080) {

                                $file_1080 = trim($movie
                                        ->video_link->url_1080, $url);

                                    if ($movie->video_link->url_1080 != '') {
                                    if (file_exists('movies_upload/url_1080/' . $file_1080)) {
                                        $file_1080 = trim($movie
                                                ->video_link->url_1080, $url);
                                            unlink('movies_upload/url_1080/' . $file_1080);
                                    }
                                }

                                $url_1080 = $request->url_1080;

                            } else {
                                $url_1080 = $request->url_1080;
                            }

                        }

                        $movie->video_link->update(['url_360' => $url_360, 'type' => 'multiquality', 'url_480' => $url_480, 'url_720' => $url_720, 'url_1080' => $url_1080, 'iframeurl' => null, 'ready_url' => null, 'upload_video' => null]);

                    }
                }

            } else {

                if ($request->selecturl == "youtubeurl" || $request->selecturl == "vimeourl" || $request->selecturl == "customurl" || $request->selecturl == "vimeoapi" || $request->selecturl == "youtubeapi") {

                    VideoLink::create(['movie_id' => $movie->id, 'type' => 'readyurl', 'ready_url' => isset($input['ready_url']) ? $input['ready_url'] : null, 'iframeurl' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'upload_video' => null]);

                } elseif ($request->selecturl == "uploadvideo") {

                    // upload video code
                    $aws = 0;
                    if ($request->upload_video != null) {

                        if ($request->upload_aws == 'on') {
                            $aws = 1;
                            $videoname = time() . $file->getClientOriginalName();
                            // aws storage
                            $t = Storage::disk('s3')->put($videoname, file_get_contents($file), 'public');
                            $file->move('movies_upload/', $videoname);

                            $upload_video = 'https://' . env('bucket') . '.s3.' . env('region') . '.amazonaws.com/movies_upload' . $videoname;
                        } else {
                            if (strstr($request->upload_video, '.mp4') || strstr($request->upload_video, '.m3u8')) {

                                $upload_video = url('movies_upload/' . $request->upload_video);

                            } else {
                                return back()->withInput()->with('deleted', __('Invalid file format Please use mp4 and m3u8 file format !'));
                            }

                        }

                        Videolink::create(['iframeurl' => null, 'ready_url' => null, 'upload_video' => $upload_video, 'type' => 'upload_video', 'ready_url' => null, 'url_360' => null, 'url_480' => null, 'url_720' => null, 'url_1080' => null, 'movie_id' => $movie->id,
                        ]);
                    }

                }

            }

            if ($menus != null) {
                if (count($menus) > 0) {
                    if (isset($movie->menus) && count($movie->menus) > 0) {
                        foreach ($movie->menus as $key => $value) {
                            $value->delete();
                        }
                    }
                    foreach ($menus as $key => $value) {
                        MenuVideo::create(['menu_id' => $value, 'movie_id' => $movie->id]);
                    }
                }
            } else {
                if (isset($movie->menus) && count($movie->menus) > 0) {
                    foreach ($movie->menus as $key => $value) {
                        $value->delete();
                    }
                }
            }

            return redirect('/admin/movies')->with('updated', __('Movie has been updated'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $movie = Movie::findOrFail($id);

        $watched = WatchHistory::where('movie_id', $id)->delete();
        $comment = MovieComment::where('movie_id',$id)->delete();

        foreach ($movie->multilinks as $multilink) {
            $multilink->delete();
        }

        $home_slider = HomeSlider::where('movie_id', $id)->delete();
        $menu_video = MenuVideo::where('movie_id', $id)->delete();
        $movie->wishlist()->delete();

        $movie_series = MovieSeries::where('movie_id', $id)->orwhere('series_movie_id', $id)->first();

        $url = url('movies_upload');

        if (isset($movie->video_link->url_360) && $movie->video_link->url_360 != '') {
            $file_360 = trim($movie->video_link->url_360, $url);

            if (file_exists('movies_upload/' . $file_360)) {
                unlink('movies_upload/' . $file_360);
            }
        }

        if (isset($movie->video_link->url_480) && $movie->video_link->url_480 != '') {
            $file_480 = trim($movie->video_link->url_480, $url);

            if (file_exists('movies_upload/' . $file_480)) {
                unlink('movies_upload/' . $file_480);
            }
        }

        if (isset($movie->video_link->url_720) && $movie->video_link->url_720 != '') {

            $file_720 = trim($movie->video_link->url_720, $url);

            if (file_exists('movies_upload/' . $file_720)) {
                unlink('movies_upload/' . $file_720);
            }

        }

        if (isset($movie->video_link->url_1080) && $movie->video_link->url_1080 != '') {
            $file_1080 = trim($movie->video_link->url_1080, $url);

            if (file_exists('movies_upload/' . $file_1080)) {
                unlink('movies_upload/' . $file_1080);
            }
        }

        if ($movie->thumbnail != null) {
            $content = @file_get_contents(public_path() . '/images/movies/thumbnails/' . $movie->thumbnail);
            if ($content) {
                unlink(public_path() . "/images/movies/thumbnails/" . $movie->thumbnail);
            }
        }
        if ($movie->poster != null) {
            $content = @file_get_contents(public_path() . '/images/movies/posters/' . $movie->poster);
            if ($content) {
                unlink(public_path() . "/images/movies/posters/" . $movie->poster);
            }
        }
        if ($movie->subtitle_files != null) {
            $content = @file_get_contents(public_path() . '/subtitles/' . $movie->subtitle_files);
            if ($content) {
                unlink(public_path() . "/subtitles/" . $movie->subtitle_files);
            }
        }
        $videolink = VideoLink::where('movie_id', $id)->first();

        if (isset($videolink)) {
            $videolink->delete();
        }
        if (isset($movie_series)) {
            $movie_series->delete();
        }

        $movie->delete();

        return back()->with('deleted', __('Movie has been deleted'));
    }

    public function bulk_delete(Request $request)
    {

        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), ['checked' => 'required']);

        if ($validator->fails()) {

            return back()
                ->with('deleted', __('Please check one of them to delete'));
        }

        foreach ($request->checked as $checked) {

            $movie = Movie::find($checked);
            if (isset($movie) && $movie != null) {
                $watched = WatchHistory::where('movie_id', $checked)->delete();
                $movie_series = MovieSeries::where('movie_id', $checked)->orwhere('series_movie_id', $checked)->get();
                foreach ($movie->multilinks as $multilink) {
                    $multilink->delete();
                }

                if ($movie->thumbnail != null) {
                    $content = @file_get_contents(public_path() . '/images/movies/thumbnails/' . $movie->thumbnail);
                    if ($content) {
                        unlink(public_path() . "/images/movies/thumbnails/" . $movie->thumbnail);
                    }
                }
                if ($movie->poster != null) {
                    $content = @file_get_contents(public_path() . '/images/movies/posters/' . $movie->poster);
                    if ($content) {
                        unlink(public_path() . "/images/movies/posters/" . $movie->poster);
                    }
                }
                if ($movie->subtitle_files != null) {
                    $content = @file_get_contents(public_path() . '/subtitles/' . $movie->subtitle_files);
                    if ($content) {
                        unlink(public_path() . "/subtitles/" . $movie->subtitle_files);
                    }
                }
                $id = $checked;
                $videolink = VideoLink::where('movie_id', $id)->first();

                $url = url('movies_upload');

                if ($movie->video_link->url_360 != '') {
                    $file_360 = trim($movie->video_link->url_360, $url);

                    if (file_exists('movies_upload/' . $file_360)) {
                        unlink('movies_upload/' . $file_360);
                    }
                }

                if ($movie->video_link->url_480 != '') {
                    $file_480 = trim($movie->video_link->url_480, $url);

                    if (file_exists('movies_upload/' . $file_480)) {
                        unlink('movies_upload/' . $file_480);
                    }
                }

                if ($movie->video_link->url_720 != '') {

                    $file_720 = trim($movie->video_link->url_720, $url);

                    if (file_exists('movies_upload/' . $file_720)) {
                        unlink('movies_upload/' . $file_720);
                    }

                }

                if ($movie->video_link->url_1080 != '') {
                    $file_1080 = trim($movie->video_link->url_1080, $url);

                    if (file_exists('movies_upload/' . $file_1080)) {
                        unlink('movies_upload/' . $file_1080);
                    }
                }

                if (isset($videolink)) {
                    $videolink->delete();
                }
                if (isset($movie_series)) {
                    MovieSeries::destroy($checked);
                }
                Movie::destroy($checked);
            } else {
                return back()->with('deleted', __('Movie not found!'));
            }

        }

        return back()->with('deleted', __('Movies has been deleted'));
    }

    /**
     * Translate the specified resource from storage.
     * Translate all tmdb movies on one click
     * @return \Illuminate\Http\Response
     */
    public function tmdb_translations()
    {
        ini_set('max_execution_time', 1000);
        $all_movies = Movie::where('tmdb', 'Y')->get();
        $TMDB_API_KEY = env('TMDB_API_KEY');

        if ($TMDB_API_KEY == null || $TMDB_API_KEY == '') {
            return back()->with('deleted', __('Please provide your TMDB api key to translate'));
        }

        if (isset($all_movies) && count($all_movies) > 0) {
            foreach ($all_movies as $key => $movie) {
                if (Session::has('changed_language')) {
                    $fetch_movie = @file_get_contents('https://api.themoviedb.org/3/movie/' . $movie->tmdb_id . '?api_key=' . $TMDB_API_KEY . '&language=' . Session::get('changed_language'));
                } else {
                    return back()->with('updated', __('Please Choose a language by admin panel top right side language menu'));
                }

                $tmdb_movie = json_decode($fetch_movie, true);
                if (isset($tmdb_movie) && $tmdb_movie != null) {
                    $movie->update(['detail' => $tmdb_movie['overview']]);
                }
            }
            return back()->with('added', __('All Movies (only by TMDB) has been translated'));
        } else {
            return back()
                ->with('updated', __('Please create at least one movie by TMDB option to translate'));
        }
    }

    public function multiplelinks($id)
    {
        $links = MultipleLinks::orderBy('id', 'desc')->where('movie_id', $id)->get();
        $language = AudioLanguage::all();
        $link = MultipleLinks::where('movie_id', $id)->get();
        return view('admin.movie.link', compact('links', 'id', 'language', 'link'));

    }

    public function storelink(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if (isset($request->download)) {
            $request->validate([
                'quality' => 'required',
                'size' => 'required',
                'language' => 'required',
                'url' => 'required',
            ]);
        }

        $input = $request->all();
        if (isset($request->download)) {
            $input['download'] = 1;
        } else {
            $input['download'] = 0;
        }
        $input['movie_id'] = $id;
        try {
            $data = MultipleLinks::create($input);
            return back()->with('added', __('Multiple links has been added'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    public function editlink(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $data = MultipleLinks::findorFail($id);

        if (isset($request->download)) {
            $request->validate([
                'quality' => 'required',
                'size' => 'required',
                'language' => 'required',
                'url' => 'required',
            ]);
        }

        $input = $request->all();
        if (isset($request->download)) {
            $input['download'] = 1;
        } else {
            $input['download'] = 0;
        }

        try {
            $data->update($input);

            return back()->with('added', __('Multiple links has been updated'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }
    }

    public function deletelink($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $delete_link = MultipleLinks::findorFail($id);
        try {
            $delete_link->delete();

            return back()->with('deleted', __('Multiple links has been deleted'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }
    }

    public function importmovies(Request $request)
    {

        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls,csv',
            ]

        );

        if ($validator->fails()) {

            return back()->with('deleted', 'Invalid file !');
        }

        $filename = 'movies_' . time() . '.' . $request->file->getClientOriginalExtension();

        Storage::disk('local')->put('/excel/' . $filename, file_get_contents($request->file->getRealPath()));

        $movies = fastexcel()->import(storage_path() . '/app/excel/' . $filename);

        if (count($movies)) {

            $movies->each(function ($item) {
                DB::beginTransaction();
                try {

                    $movie = Movie::create([

                        'title' => $item['title'] != null ? $item['title'] : null,
                        'slug' => $item['title'] != null ? str_slug($item['title'], '-') : null,
                        'keyword' => $item['keyword'] != null ? $item['keyword'] : null,
                        'description' => $item['description'] != null ? $item['description'] : null,
                        'duration' => $item['duration'] != null ? $item['duration'] : null,
                        'thumbnail' => $item['thumbnail'] != null ? $item['thumbnail'] : null,
                        'poster' => $item['poster'] != null ? $item['poster'] : null,
                        'tmdb' => 'N',
                        'fetch_by' => 'title',
                        'director_id' => $item['director_id'] != null ? $item['director_id'] : null,
                        'actor_id' => $item['actor_id'] != null ? $item['actor_id'] : null,
                        'genre_id' => $item['genre_id'] != null ? $item['genre_id'] : null,
                        'trailer_url' => $item['trailer_url'] != null ? $item['trailer_url'] : null,
                        'detail' => $item['detail'] != null ? $item['detail'] : null,
                        'rating' => $item['rating'] != null ? $item['rating'] : null,
                        'maturity_rating' => $item['maturity_rating'] != null ? $item['maturity_rating'] : 'all age',
                        'subtitle' => $item['subtitle'] != null ? 1 : 0,
                        'publish_year' => $item['publish_year'] != null ? $item['publish_year'] : null,
                        'released' => $item['released'] != null ? $item['released'] : null,
                        'featured' => $item['featured'] != null ? 1 : 0,
                        'series' => $item['series'] != null ? 1 : 0,
                        'a_language' => $item['a_language'] != null ? $item['a_language'] : null,
                        'type' => 'M',
                        'live' => 0,
                        'livetvicon' => null,
                        'status' => 1,
                        'tmdb_id' => null,
                        'is_protect' => $item['is_protect'] != null ? 1 : 0,
                        'password' => $item['password'] != null ? Crypt::encrypt($item['password']) : null,
                        'created_by' => auth()->user()->id,
                        'is_upcoming' => $item['is_upcoming'] != null ? 1 : 0,
                        'upcoming_date' => $item['upcoming_date'] != null ? $item['upcoming_date'] : null,
                        'is_custom_label' => $item['is_custom_label'] != null ? 1 : 0,
                        'label_id' => $item['label_id'] != null ? $item['label_id'] : null,

                    ]);

                    if (isset($item['menu']) && $item['menu'] != null) {
                        $menus_ids = explode(',', $item['menu']);

                        foreach ($menus_ids as $value) {

                            MenuVideo::create(['menu_id' => $value, 'movie_id' => $movie->id]);
                        }
                    }

                    if (isset($item['selecturl']) && $item['selecturl'] != null) {
                        if ($item['selecturl'] == 'iframe') {
                            $iframeurl = $item['url'];
                            $type = 'iframeurl';
                        } elseif ($item['selecturl'] == 'youtube' || $item['selecturl'] == 'vimeo' || $item['selecturl'] == 'custom') {
                            $url = $item['url'];
                            $type = 'readyurl';
                        } elseif ($item['selecturl'] == 'upload') {
                            $uploadurl = $item['upload_video'];
                            $type = 'upload_video';
                        } else {
                            $type = 'multiquality';
                            $url360 = $item['url_360'];
                            $url480 = $item['url_480'];
                            $url720 = $item['url_720'];
                            $url1080 = $item['url_1080'];
                        }

                        Videolink::create([
                            'movie_id' => $movie->id,
                            'type' => $type,
                            'iframeurl' => isset($iframeurl) && $iframeurl != null ? $iframeurl : null,
                            'ready_url' => isset($url) && $url != null ? $url : null,
                            'upload_video' => isset($uploadurl) && $uploadurl != null ? $uploadurl : null,
                            'url_360' => isset($url360) && $url360 != null ? $url360 : null,
                            'url_480' => isset($url480) && $url480 != null ? $url480 : null,
                            'url_720' => isset($url720) && $url720 != null ? $url720 : null,
                            'url_1080' => isset($url1080) && $url1080 != null ? $url1080 : null,
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    return back()->with('deleted', $e->getMessage());
                }

            });

            unlink(storage_path() . '/app/excel/' . $filename);

            return back()->with('added', __('Movies imported successfully'));

        } else {

            return back()->with('deleted', __('File is empty !'));
        }

    }

}
