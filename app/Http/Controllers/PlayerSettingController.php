<?php

namespace App\Http\Controllers;

use App\PlayerSetting;
use Illuminate\Http\Request;


class PlayerSettingController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('permission:site-settings.player-setting', ['only' => ['get', 'update']]);

    }
    public function get()
    {
        $ps = PlayerSetting::first();
        return view('admin.player-setting.edit', compact('ps'));
    }

    public function update(Request $request)
    {

        $ps = PlayerSetting::first();
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        if ($request->logo_enable) {
            $request->validate([
                'logo' => 'mimes:jpg,jpeg,png,gif',
            ]);
        }

        $ps->cpy_text = $request->cpy_text;
        $ps->share_opt = $request->share_opt;
        $ps->auto_play = $request->auto_play;
        $ps->speed = $request->speed;
        $ps->thumbnail = $request->thumbnail;
        $ps->info_window = $request->info_window;
        $ps->skin = $request->skin;
        $ps->loop_video = $request->loop_video;
        $ps->logo_enable = $request->logo_enable ? 1 : 0;
        $ps->is_resume = $request->is_resume;
        $ps->player_google_analytics_id = $request->player_google_analytics_id;
        $ps->subtitle_font_size = $request->subtitle_font_size;
        $ps->subtitle_color = $request->subtitle_color;
        $ps->chromecast = $request->chromecast;

        if ($request->logo_enable) {
            if ($file = $request->file('logo')) {

                $name = 'logo.png';
                $path = 'content/' . $ps->skin . '/';
                if ($ps->logo != "") {

                    $logo = @file_get_contents('content/' . $ps->skin . '/' . $ps->logo);
                }
                if (isset($logo) && $logo != null) {
                    unlink('content/' . $ps->skin . '/' . $ps->logo);
                    $file->move('content/' . $ps->skin . '/', $name);
                } else {
                    $file->move('content/' . $ps->skin . '/', $name);
                }
                $ps->logo = $name;
            }
            $ps->logo_enable = 1;
        }

        $ps->save();

        return back()->with('updated', __('Player Settings Updated !'));
    }
}
