<?php

namespace App\Http\Controllers;

use App\button;
use Illuminate\Http\Request;


class ButtonController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $button = Button::whereId(1)->first();
        return view('admin.button.index', compact('button'));
    }

    public function update(Request $request, $id)
    {
        $button = Button::find($id);
        if (!isset($input['inspect'])) {
            $input['inspect'] = 0;
        }
        if (!isset($input['rightclick'])) {
            $input['rightclick'] = 0;
        }

        if (!isset($input['goto'])) {
            $input['goto'] = 0;
        }

        if (!isset($input['color'])) {
            $input['color'] = 0;
        }

        if (!isset($input['uc_browser'])) {
            $input['uc_browser'] = 0;
        }
        if (!isset($input['comming_soon'])) {
            $input['comming_soon'] = 0;
        } else {
            $input['comming_soon'] = 1;
        }

        $button->update($input);
        return back()->with('updated', __('Settings has been updated'));
    }
}
