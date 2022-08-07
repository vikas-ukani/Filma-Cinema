<?php

namespace App\Http\Controllers;

use App\HeaderTranslation;
use Illuminate\Http\Request;


class HeaderTranslationController extends Controller
{
   
    public function index()
    {
        $translations = HeaderTranslation::all();
        return view('admin.translations.header-translations', compact('translations'));
    }

    public function update(Request $request)
    {
        $ids = $request->id;
        $values = $request->name;

        foreach ($ids as $key => $value) {
            $get_key = HeaderTranslation::find($value);
            if (isset($get_key)) {
                $get_key->update([
                    'value' => $values[$key],
                ]);
            }
        }

        return back()->with('updated', __('Header translations has been updated'));

    }
}
