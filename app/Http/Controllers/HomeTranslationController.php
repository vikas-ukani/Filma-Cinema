<?php

namespace App\Http\Controllers;

use App\HomeTranslation;
use Illuminate\Http\Request;


class HomeTranslationController extends Controller
{
  
    public function index()
    {
        $translations = HomeTranslation::all();
        return view('admin.translations.home-translations', compact('translations'));
    }

    public function update(Request $request)
    {
        $ids = $request->id;
        $values = $request->name;

        foreach ($ids as $key => $value) {
            $get_key = HomeTranslation::find($value);
            if (isset($get_key)) {
                $get_key->update([
                    'value' => $values[$key],
                ]);
            }
        }

        return back()->with('updated', __('Home page translations has been updated'));

    }
}
