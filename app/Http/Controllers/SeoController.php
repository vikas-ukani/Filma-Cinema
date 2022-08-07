<?php

namespace App\Http\Controllers;

use App\seo;
use Illuminate\Http\Request;


class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:site-settings.seo', ['only' => ['index', 'update']]);

    }

    public function index()
    {
        $seo = Seo::whereId(1)->first();
        return view('admin.seo', compact('seo'));
    }

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $seo = Seo::findOrFail($id);
        $input = $request->all();
        $seo->update($input);
        return back()->with('updated', __('Seo has been updated'));
    }

}
