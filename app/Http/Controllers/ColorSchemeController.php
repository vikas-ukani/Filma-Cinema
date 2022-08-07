<?php

namespace App\Http\Controllers;

use App\ColorScheme;
use Illuminate\Http\Request;


class ColorSchemeController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:site-settings.color-option', ['only' => ['index', 'store']]);
    }

    public function index()
    {
        $color_scheme = ColorScheme::first();
        return view('admin.color_scheme.index', compact('color_scheme'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $color = ColorScheme::first();
        $input = $request->all();
        if ($request->reset == "Reset to Default") {
            $input['color_scheme'] = $request->color_scheme;
            $input['custom_navigation_color'] = null;
            $input['custom_text_color'] = null;
            $input['custom_text_on_color'] = null;
            $input['custom_back_to_top_bgcolor'] = null;
            $input['custom_back_to_top_bgcolor_on_hover'] = null;
            $input['custom_back_to_top_color'] = null;
            $input['custom_back_to_top_color_on_hover'] = null;
            $input['custom_footer_background_color'] = null;

            $color->update($input);
            return back()->with('updated', __('Color scheme set to default!'));
        } elseif ($request->save == 'Save Settings') {

            $color->update($input);
            return back()->with('added', __('Color scheme Updated!'));
        }

    }

}
