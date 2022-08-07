<?php

namespace App\Http\Controllers;

use App\Adsense;
use Illuminate\Http\Request;


class AdsenseController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:site-settings.adsense', ['only' => ['index', 'update']]);
    }

    public function index()
    {
        $ad = Adsense::first();
        return view('admin.adsense.index', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $ad = Adsense::findorfail($id);
        $input = $request->all();

        if ($request->status == 0) {
            $input['status'] = 0;
        } else {
            $input['status'] = 1;
        }
        if ($request->ishome == 0) {
            $input['ishome'] = 0;
        } else {
            $input['ishome'] = 1;
        }
        if ($request->isviewall == 0) {
            $input['isviewall'] = 0;
        } else {
            $input['isviewall'] = 1;
        }
        if ($request->issearch == 0) {
            $input['issearch'] = 0;
        } else {
            $input['issearch'] = 1;
        }
        if ($request->iswishlist == 0) {
            $input['iswishlist'] = 0;
        } else {
            $input['iswishlist'] = 1;
        }
        $ad->update($input);

        if ($ad->status == 0) {
            return redirect()->route('adsense')->with('updated', __('Adsense is now Deactive !'));
        } else {
            return redirect()->route('adsense')->with('updated', __('Adsense is now Active !'));
        }

    }

}
