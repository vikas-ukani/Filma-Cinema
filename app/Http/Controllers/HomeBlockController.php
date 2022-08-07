<?php

namespace App\Http\Controllers;

use App\HomeBlock;
use App\Movie;
use App\TvSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class HomeBlockController extends Controller
{
   

    public function __construct()
    {
        $this->middleware('permission:front-settings.short-promo', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bluk_delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $home_blocks = HomeBlock::orderBy('id', 'asc')->get();
        return view('admin.home-block.index', compact('home_blocks'));
    }
    /**
     * Show the form for creating the specified resource.
     *
     * @param  \App\Coupon  $id
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $movie_list = Movie::pluck('title', 'id')->all();
        $tv_series_list = TvSeries::pluck('title', 'id')->all();
        return view('admin.home-block.create', compact('movie_list', 'tv_series_list'));
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        $input = $request->all();
        if (!isset($input['is_active'])) {
            $input['is_active'] = 0;
        }

        HomeBlock::create($input);

        return back()->with('added', __('Promotion Settings Block has been added'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupon  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $home_block = HomeBlock::findOrFail($id);
        if ($home_block->movie_id != null) {
            $movie_dtl = Movie::findOrFail($home_block->movie_id);
            $movie_list = Movie::pluck('title', 'id')->all();
            $tv_series_list = TvSeries::pluck('title', 'id')->all();
            return view('admin.home-block.edit', compact('home_block', 'movie_list', 'tv_series_list', 'movie_dtl'));
        } elseif ($home_block->tv_series_id != null) {
            $tv_series_dtl = TvSeries::findOrFail($home_block->tv_series_id);
            $movie_list = Movie::pluck('title', 'id')->all();
            $tv_series_list = TvSeries::pluck('title', 'id')->all();
            return view('admin.home-block.edit', compact('home_block', 'movie_list', 'tv_series_list', 'tv_series_dtl'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $input = $request->all();

        $home_block = HomeBlock::findOrFail($id);
        if (!isset($input['is_active'])) {
            $input['is_active'] = 0;
        }

        $home_block->update($input);

        return redirect('admin/home-block')->with('updated', __('Promotion Settings Block has been updated'));
    }

    public function destroy($id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $home_block = HomeBlock::findOrFail($id);
        $home_block->delete();

        return back()->with('deleted', __('Promotion Settings Block has been deleted'));
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
            $home_block = HomeBlock::findOrFail($checked);

            $home_block->delete();
        }
        return back()->with('deleted', __('Promotion Settings Block has been deleted'));
    }

}
