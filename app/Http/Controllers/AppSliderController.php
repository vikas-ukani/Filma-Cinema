<?php

namespace App\Http\Controllers;

use App\AppSlider;
use App\Movie;
use App\TvSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AppSliderController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:app-settings.slider', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bulk_delete', 'slide_reposition']]);
    }

    public function index()
    {

        $app_slides = AppSlider::orderBy('position', 'asc')->get();
        return view('admin.appslider.index', compact('app_slides'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $movie_list = Movie::pluck('title', 'id')->all();
        $tv_series_list = TvSeries::pluck('title', 'id')->all();
        return view('admin.appslider.create', compact('movie_list', 'tv_series_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'slide_image' => 'required|image|mimes:png,jpeg,jpg,gif,webp',
        ]);

        $input = $request->all();

        if ($file = $request->file('slide_image')) {
            $name = 'app_slide_' . time() . $file->getClientOriginalName();
            if ($request->movie_id != null && $request->movie_id != '') {
                $file->move('images/app_slider/movies/', $name);
            } elseif ($request->tv_series_id != null && $request->tv_series_id != '') {
                $file->move('images/app_slider/shows/', $name);
            } else {
                $file->move('images/app_slider/', $name);
            }
            $input['slide_image'] = $name;

        }

        if (!isset($input['active'])) {
            $input['active'] = 0;
        }

        $input['position'] = (AppSlider::count() + 1);

        AppSlider::create($input);

        return back()->with('added', 'App Slide has been added');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppSlider  $appSlider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $app_slide = AppSlider::findOrFail($id);
        if ($app_slide->movie_id != null) {
            $movie_dtl = Movie::findOrFail($app_slide->movie_id);
            $movie_list = Movie::pluck('title', 'id')->all();
            $tv_series_list = TvSeries::pluck('title', 'id')->all();
            return view('admin.appslider.edit', compact('app_slide', 'movie_list', 'tv_series_list', 'movie_dtl'));
        } elseif ($app_slide->tv_series_id != null) {
            $tv_series_dtl = TvSeries::findOrFail($app_slide->tv_series_id);
            $movie_list = Movie::pluck('title', 'id')->all();
            $tv_series_list = TvSeries::pluck('title', 'id')->all();
            return view('admin.appslider.edit', compact('app_slide', 'movie_list', 'tv_series_list', 'tv_series_dtl'));
        } else {
            $movie_list = Movie::pluck('title', 'id')->all();
            $tv_series_list = TvSeries::pluck('title', 'id')->all();
            return view('admin.appslider.edit', compact('app_slide', 'movie_list', 'tv_series_list'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppSlider  $appSlider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $slide = AppSlider::findOrFail($id);
        $request->validate([
            'slide_image' => 'required|image|mimes:png,jpeg,jpg,gif,webp',
        ]);

        $input = $request->all();

        $input = $request->all();

        if ($file = $request->file('slide_image')) {
            $name = 'app_slide_' . time() . $file->getClientOriginalName();
            if ($request->movie_id != null && $request->movie_id != '') {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/app_slider/movies/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/app_slider/movies/' . $slide->slide_image);
                    }
                }
                $file->move('images/app_slider/movies/', $name);
            } elseif ($request->tv_series_id != null && $request->tv_series_id != '') {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/app_slider/shows/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/app_slider/shows/' . $slide->slide_image);
                    }
                }
                $file->move('images/app_slider/shows/', $name);
            } else {
                if ($slide->slide_image != null) {
                    $image_file = @file_get_contents(public_path() . '/images/app_slider/' . $slide->slide_image);
                    if ($image_file) {
                        unlink(public_path() . '/images/app_slider/' . $slide->slide_image);
                    }
                }
                $file->move('images/app_slider/', $name);
            }
            $input['slide_image'] = $name;

        }

        if (!isset($input['active'])) {
            $input['active'] = 0;
        }

        $slide->update($input);
        return redirect('admin/appslider')->with('updated', __('App Slide has been updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AppSlider  $appSlider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $app_slide = AppSlider::findOrFail($id);

        if ($app_slide->slide_image != null) {
            if ($app_slide->movie_id != null) {
                $content = @file_get_contents(public_path() . '/images/app_slider/movies/' . $app_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/app_slider/movies/' . $app_slide->slide_image);
                }
            } elseif ($app_slide->tv_series_id != null) {
                $content = @file_get_contents(public_path() . '/images/app_slider/shows/' . $app_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/app_slider/shows/' . $app_slide->slide_image);
                }
            } else {
                $content = @file_get_contents(public_path() . '/images/app_slider/' . $app_slide->slide_image);
                if ($content) {
                    unlink(public_path() . '/images/app_slider/' . $app_slide->slide_image);
                }
            }
        }
        $app_slide->delete();
        return back()->with('deleted', __('App Slide has been deleted'));
    }

    public function slide_reposition(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if($request->ajax()){

            $posts = AppSlider::all();
            foreach ($posts as $post) {
                foreach ($request->order as $order) {
                    if ($order['id'] == $post->id) {
                        \DB::table('app_sliders')->where('id',$post->id)->update(['position' => $order['position']]);
                    }
                }
            }
            return response()->json('Update Successfully.', 200);

        }
    }

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
            $app_slide = AppSlider::findOrFail($checked);

            if ($app_slide->slide_image != null) {
                if ($app_slide->movie_id != null) {
                    $content = @file_get_contents(public_path() . '/images/app_slider/movies/' . $app_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/app_slider/movies/' . $app_slide->slide_image);
                    }
                } else if ($app_slide->tv_series_id != null) {
                    $content = @file_get_contents(public_path() . '/images/app_slider/shows/' . $app_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/app_slider/shows/' . $app_slide->slide_image);
                    }
                } else {
                    $content = @file_get_contents(public_path() . '/images/app_slider/' . $app_slide->slide_image);
                    if ($content) {
                        unlink(public_path() . '/images/app_slider/' . $app_slide->slide_image);
                    }
                }
            }

            $app_slide->delete();
        }

        return back()->with('deleted', __('App Slides has been deleted'));
    }
}
