<?php

namespace App\Http\Controllers;

use App\Subtitles;
use Illuminate\Http\Request;


class SubtitleController extends Controller
{
    public function post(Request $request, $id)
    {
        if ($request->has('sub_t')) {
            foreach ($request->file('sub_t') as $key => $image) {

                $name = $image->getClientOriginalName();
                $image->move(public_path() . '/subtitles/', $name);

                $form = new Subtitles();
                $form->sub_lang = $request->sub_lang[$key];
                $form->sub_t = $name;
                $form->m_t_id = $id;
                $form->save();
            }
        }

        return back()->with('success',__('Subtitle added !'));
    }

    public function delete($id)
    {
        $record = Subtitles::findorfail($id);
        if ($record->sub_t != "") {

            $file = @file_get_contents('subtitles/' . $record->sub_t);
            if (file_exists($file)) {
                unlink('subtitles/' . $record->sub_t);
                $record->delete();
            } else {
                $record->delete();
            }
        }

        $record->delete();

        return back()->with('deleted', __('Subtitle has been deleted !'));
    }
}
