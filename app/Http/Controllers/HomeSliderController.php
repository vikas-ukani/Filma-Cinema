<?php

namespace App\Http\Controllers;

use App\HomeSlider;
use App\Movie;
use App\TvSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;


class HomeSliderController extends Controller
{
    
    public function __construct()
    {

        $this->middleware('permission:front-settings.sliders', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bluk_delete', 'slide_reposition']]);

        $this->homeslider = HomeSlider::query();
        $this->movie = Movie::query();
        $this->tvseries = TvSeries::query();
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $home_slides = $this->homeslider->orderBy('position', 'asc')->get();
        return view('admin.homeslider.index', compact('home_slides'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $movie_list = $this->movie->pluck('title', 'id')->all();
        $tv_series_list = $this->tvseries->pluck('title', 'id')->all();
        return view('admin.homeslider.create', compact('movie_list', 'tv_series_list'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $request->validate([
            'slide_image' => 'required|image|mimes:png,jpeg,jpg,gif,webp',
        ]);

        $input = $request->all();

        if ($file = $request->file('slide_image')) {
            $name = 'slide_' . time() . $file->getClientOriginalName();
            if ($request->movie_id != null && $request->movie_id != '') {
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider/movies') . '/' . $name);

            } elseif ($request->tv_series_id != null && $request->tv_series_id != '') {
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider/shows') . '/' . $name);

            } else {
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider') . '/' . $name);

            }
            $input['slide_image'] = $name;
        }

        if (!isset($input['active'])) {
            $input['active'] = 0;
        }
        if (isset($input['is_kids'])) {
            $input['is_kids'] = 1;
        }else{
            $input['is_kids'] = 0;
        }
      

        $input['position'] = ($this->homeslider->count() + 1);
        try {

            $this->homeslider->create($input);

            return back()->with('added', __('Slide has been added'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $home_slide = $this->homeslider->find($id);
        if ($home_slide->movie_id != null) {
            $movie_dtl = $this->movie->find($home_slide->movie_id);
            $movie_list = $this->movie->pluck('title', 'id')->all();
            $tv_series_list = $this->tvseries->pluck('title', 'id')->all();
            return view('admin.homeslider.edit', compact('home_slide', 'movie_list', 'tv_series_list', 'movie_dtl'));
        } elseif ($home_slide->tv_series_id != null) {
            $tv_series_dtl = $this->tvseries->find($home_slide->tv_series_id);
            $movie_list = $this->movie->pluck('title', 'id')->all();
            $tv_series_list = $this->tvseries->pluck('title', 'id')->all();
            return view('admin.homeslider.edit', compact('home_slide', 'movie_list', 'tv_series_list', 'tv_series_dtl'));
        } else {
            $movie_list = $this->movie->pluck('title', 'id')->all();
            $tv_series_list = $this->tvseries->pluck('title', 'id')->all();
            return view('admin.homeslider.edit', compact('home_slide', 'movie_list', 'tv_series_list'));
        }

    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $input = $request->all();
        $slide = $this->homeslider->find($id);

        if ($slide->slide_image == null) {
            $request->validate([
                'slide_image' => 'required|image|mimes:png,jpeg,jpg,gif,webp',
            ]);
        }

        if ($file = $request->file('slide_image')) {
            $name = 'slide_' . time() . $file->getClientOriginalName();
            if ($request->movie_id != null && $request->movie_id != '') {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/home_slider/movies/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/home_slider/movies/' . $slide->slide_image);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider/movies') . '/' . $name);

            } elseif ($request->tv_series_id != null && $request->tv_series_id != '') {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/home_slider/shows/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/home_slider/shows/' . $slide->slide_image);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider/shows') . '/' . $name);

            } else {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/home_slider/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/home_slider/' . $slide->slide_image);
                    }
                }
                $img = Image::make($file->path());

                $img->resize(1534, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(public_path('/images/home_slider') . '/' . $name);

            }
            $input['slide_image'] = $name;

        }

        if (!isset($input['active'])) {
            $input['active'] = 0;
        }

        if (isset($input['is_kids'])) {
            $input['is_kids'] = 1;
        }else{
            $input['is_kids'] = 0;
        }
       
        try {
            $slide->update($input);
            return redirect('admin/home_slider')->with('updated', __('Slide has been updated'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $home_slide = $this->homeslider->find($id);

        if ($home_slide->slide_image != null) {
            if ($home_slide->movie_id != null) {
                $content = @file_get_contents(public_path() . '/images/home_slider/movies/' . $home_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/home_slider/movies/' . $home_slide->slide_image);
                }
            } elseif ($home_slide->tv_series_id != null) {
                $content = @file_get_contents(public_path() . '/images/home_slider/shows/' . $home_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/home_slider/shows/' . $home_slide->slide_image);
                }
            } else {
                $content = @file_get_contents(public_path() . '/images/home_slider/' . $home_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/home_slider/' . $home_slide->slide_image);
                }
            }
        }
        $home_slide->delete();
        return back()->with('deleted', __('Slide has been deleted'));
    }

    public function slide_reposition(Request $request)
    {
       
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if($request->ajax()){

            $posts = HomeSlider::all();
            foreach ($posts as $post) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $post->id) {
                        \DB::table('home_sliders')->where('id',$post->id)->update(['position' => $order['position']]);
                    }
                }
            }
            return response()->json('Update Successfully.', 200);

        }
      
    }

    /**
     * Bulk Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function bulk_delete(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $validator = Validator::make($request->all(), [
            'checked' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('deleted', __('Please select one of them to delete'));
        }

        foreach ($request->checked as $checked) {
            $home_slide = $this->homeslider->find($checked);

            if ($home_slide->slide_image != null) {
                if ($home_slide->movie_id != null) {
                    $content = @file_get_contents(public_path() . '/images/home_slider/movies/' . $home_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/home_slider/movies/' . $home_slide->slide_image);
                    }
                } else if ($home_slide->tv_series_id != null) {
                    $content = @file_get_contents(public_path() . '/images/home_slider/shows/' . $home_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/home_slider/shows/' . $home_slide->slide_image);
                    }
                } else {
                    $content = @file_get_contents(public_path() . '/images/home_slider/' . $home_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/home_slider/' . $home_slide->slide_image);
                    }
                }
            }

            $home_slide->delete();
        }

        return back()->with('deleted', __('Slides has been deleted'));
    }
}
