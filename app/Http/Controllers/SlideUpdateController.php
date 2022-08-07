<?php

namespace App\Http\Controllers;

use App\FrontSliderUpdate;
use App\Movie;
use App\Season;
use Illuminate\Http\Request;


class SlideUpdateController extends Controller
{
    public function get()
    {
        $movie_max = count(Movie::all());
        $season_max = count(Season::all());
        return view('admin.sliderlimit.index', compact('movie_max', 'season_max'));
    }

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $find = FrontSliderUpdate::findorfail($id);

        $request->validate([
            'item_show' => 'min:1',
        ]);

        $find->item_show = $request->item_show;

        if (isset($request->order)) {
            $find->orderby = 1;
        } else {

            $find->orderby = 0;
        }
        if (isset($request->slider)) {
            $find->sliderview = 1;
        } else {

            $find->sliderview = 0;
        }

        $find->save();

        return redirect()->route('front.slider.limit')->with('updated', __('Slider Limit Updated !'));

    }
}
