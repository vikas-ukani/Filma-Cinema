<?php

namespace App\Http\Controllers;

use App\PopoverTranslation;
use Illuminate\Http\Request;


class PopoverTranslationController extends Controller
{
    public function index()
    {
        $translations = PopoverTranslation::all();
        return view('admin.translations.popover-translations', compact('translations'));
    }

    public function update(Request $request)
    {
        $ids = $request->id;
        $values = $request->name;

        foreach ($ids as $key => $value) {
            $get_key = PopoverTranslation::find($value);
            if (isset($get_key)) {
                $get_key->update([
                    'value' => $values[$key],
                ]);
            }
        }

        return back()->with('updated', __('Popover detail keys has been translated'));

    }
}
