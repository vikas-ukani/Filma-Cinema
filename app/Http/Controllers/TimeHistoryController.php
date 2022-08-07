<?php

namespace App\Http\Controllers;

use App\PlayerSetting;
use App\TimeHistory;
use App\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class TimeHistoryController extends Controller
{
    public function watchhistory($movie_id, $type)
    {
        $user_id = Auth::user()->id;
        if (isset($type) && $type == 'movie') {
            $exists = WatchHistory::where('movie_id', $movie_id)->where('user_id', $user_id)->first();
            if (!isset($exists)) {

                WatchHistory::create([
                    'movie_id' => $movie_id,
                    'user_id' => $user_id,
                ]);
            }
        } else if (isset($type) && $type == 'tv') {
            $exists = WatchHistory::where('tv_id', $movie_id)->where('user_id', $user_id)->first();

            if (!isset($exists)) {

                WatchHistory::create([
                    'tv_id' => $movie_id,
                    'user_id' => $user_id,
                ]);
            }
        }

    }

    public function movie_time($endtime, $movie_id, $user_id, $totaltime)
    {

        $exists = WatchHistory::where('movie_id', $movie_id)->where('user_id', $user_id)->first();
        $player_settings = PlayerSetting::first();
        if (!isset($exists)) {

            WatchHistory::create([
                'movie_id' => $movie_id,
                'user_id' => $user_id,
            ]);
        }

        $timeold = $endtime;
        if (strlen($endtime) <= 5) {

            $endtime = '00:' . $endtime;
        }

        if ($player_settings->is_resume == 1) {

            $time = array(
                'user_id' => $user_id,
                'movie_id' => $movie_id,
                'total_time' => $totaltime,
                'current_time' => $endtime,
                'curDurration' => request()->curDurration,
                'totalDuration' => request()->totalDuration,

            );

            $file = json_encode($time, JSON_PRETTY_PRINT);
            $filename = 'time.json';

            Storage::disk('local')->put('/time/movie/user_' . $user_id . '/movie_' . $movie_id . '/' . $filename, $file);

            $result = TimeHistory::where('user_id', $user_id)->where('movie_id', $movie_id)->first();
            if (!$result) {
                $filename = 'time.json';
                $file = url('storage/app/time/movie/user_' . $user_id . '/movie_' . $movie_id . '/' . $filename);
                TimeHistory::create([
                    'user_id' => $user_id,
                    'movie_id' => $movie_id,
                    'file' => $file,

                ]);
            }
        }

    }

    public function tv_time($endtime, $tv_id, $user_id, $totaltime)
    {
        $exists = WatchHistory::where('tv_id', $tv_id)->where('user_id', $user_id)->first();
        $player_settings = PlayerSetting::first();
        if (!isset($exists)) {

            WatchHistory::create([
                'tv_id' => $tv_id,
                'user_id' => $user_id,
            ]);
        }

        $timeold = $endtime;
        if (strlen($endtime) <= 5) {

            $endtime = '00:' . $endtime;
        }

        $times = Session::get('time_' . $tv_id);

        if (isset($times) && !is_null($times)) {

            foreach ($times as $key => $value) {
                $v[] = $value;
            }

            $coll = collect($v)->unique()->flatten();

            if ($coll->contains($tv_id) && isset($times['endtime']) && strtotime($times['endtime']) <= strtotime($timeold)) {

                session()->put('time_' . $tv_id, [
                    'endtime' => $endtime,
                    'tv_id' => $tv_id,
                    'user' => $user_id,
                ]);

            } else {
                if (isset($times['endtime']) && strtotime($times['endtime']) <= strtotime($timeold)) {
                    session()->push('time_' . $tv_id, [
                        'endtime' => $endtime,
                        'tv_id' => $tv_id,
                        'user' => $user_id,
                    ]);
                }

            }

        } else {
            if (isset($endtime) <= strtotime($timeold)) {
                session()->put('time_' . $tv_id, [
                    'endtime' => $endtime,
                    'tv_id' => $tv_id,
                    'user' => $user_id,
                ]);
            }

        }

        if ($player_settings->is_resume == 1) {

            $time = array(
                'user_id' => $user_id,
                'tv_id' => $tv_id,
                'total_time' => $totaltime,
                'current_time' => $endtime,
            );

            $file = json_encode($time, JSON_PRETTY_PRINT);
            $filename = 'time.json';

            Storage::disk('local')->put('/time/tv/' . $user_id . '/' . $tv_id . '/' . $filename, $file);

            $result = TimeHistory::where('user_id', $user_id)->where('tv_id', $tv_id)->first();
            if (!$result) {
                $filename = 'time.json';
                $file = url('storage/app/time/tv/' . $user_id . '/' . $tv_id . '/' . $filename);
                TimeHistory::create([
                    'user_id' => $user_id,
                    'tv_id' => $tv_id,
                    'file' => $file,

                ]);
            }
        }
        return $times['endtime'];
    }

    public function episode_time($endtime, $episode_id, $user_id, $tv_id, $totaltime)
    {

        $exists = WatchHistory::where('tv_id', $tv_id)->where('user_id', $user_id)->first();
        $player_settings = PlayerSetting::first();
        if (!isset($exists) && is_null($exists)) {
            WatchHistory::create([
                'tv_id' => $tv_id,
                'user_id' => $user_id,
            ]);
        }

        $timeold = $endtime;
        if (strlen($endtime) <= 5) {

            $endtime = '00:' . $endtime;
        }

        $times = Session::get('time_' . $tv_id . $episode_id);

        if (isset($times) && !is_null($times)) {

            foreach ($times as $key => $value) {
                $v[] = $value;
            }

            $coll = collect($v)->unique()->flatten();

            if ($coll->contains($episode_id) && isset($times['endtime']) && strtotime($times['endtime']) <= strtotime($timeold)) {

                session()->put('time_' . $tv_id . $episode_id, [
                    'endtime' => $endtime,
                    'episode_id' => $episode_id,
                    'user' => $user_id,
                ]);

            } else {
                if (isset($times['endtime']) && strtotime($times['endtime']) <= strtotime($timeold)) {
                    session()->push('time_' . $tv_id . $episode_id, [
                        'endtime' => $endtime,
                        'episode_id' => $episode_id,
                        'user' => $user_id,
                    ]);
                }

            }

        } else {
            if (isset($endtime) <= strtotime($timeold)) {
                session()->put('time_' . $tv_id . $episode_id, [
                    'endtime' => $endtime,
                    'episode_id' => $episode_id,
                    'user' => $user_id,
                ]);
            }

        }

        if ($player_settings->is_resume == 1) {

            $time = array(
                'user_id' => $user_id,
                'tv_id' => $tv_id,
                'episode_id' => $episode_id,
                'total_time' => $totaltime,
                'current_time' => $endtime,
                'curDurration' => request()->curDurration,
                'totalDuration' => request()->totalDuration,
            );

            $file = json_encode($time, JSON_PRETTY_PRINT);
            $filename = 'time.json';

            Storage::disk('local')->put('/time/tv/user_' . $user_id . '/episode_' . $episode_id . '/' . $filename, $file);

            $result = TimeHistory::where('user_id', $user_id)->where('tv_id', $tv_id)->where('episode_id', $episode_id)->first();
            if (!$result) {
                $filename = 'time.json';
                $file = url('storage/app/time/tv/user_' . $user_id . '/episode_' . $tv_id . '/' . $filename);
                TimeHistory::create([
                    'user_id' => $user_id,
                    'tv_id' => $tv_id,
                    'episode_id' => $episode_id,
                    'file' => $file,

                ]);
            }

            return $endtime;
        }

    }

}
