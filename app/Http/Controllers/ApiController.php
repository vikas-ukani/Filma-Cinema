<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Season;


class ApiController extends Controller
{
    
    public function get_video_data($id, $type)
    {
        $poster = null;
        $filterd_links = null;
        $all_data = null;
        if ($type == 'M') {

            $movie = Movie::findOrFail($id);

            $poster = $movie->poster;

            $all_link = $movie->video_link;

            $filterd_links = collect();

            if ($all_link->ready_url != null) {
                $myurl = $all_link->ready_url;
                $rest = substr($myurl, 0, 14);
                $rest2 = substr($myurl, 0, 20);
                $type = null;
                if ($rest == "https://youtu." || $rest2 == "https://www.youtube.") {
                    $type = 'video/youtube';
                } elseif ($rest == 'https://vimeo.') {
                    $type = 'video/vimeo';
                } else {
                    $type = 'video/mp4';
                }

                $link = [
                    'src' => $all_link->ready_url,
                    'type' => $type,
                ];

                $filterd_links->push($link);

            } else {

                if ($all_link->url_360 != null) {
                    $myurl = $all_link->url_360;
                    $rest = substr($myurl, -4);
                    $rest2 = substr($myurl, -2);
                    $type = null;
                    if ($rest == ".mp4") {
                        $type = 'video/mp4';

                    } elseif ($rest == "webm") {
                        $type = 'video/webm';

                    } elseif ($rest == 'm3u8') {
                        $type = 'application/x-mpegURL';

                    } elseif ($rest == '.3gp') {
                        $type = 'video/3gp';

                    } elseif ($rest2 == 'ts') {
                        $type = 'application/x-mpegURL';

                    } else {
                        $type = 'video/mp4';
                    }

                    $link = [
                        'src' => $all_link->url_360,
                        'type' => $type,
                        'label' => '360',
                        'res' => 360,
                    ];
                    $filterd_links->push($link);
                }
                if ($all_link->url_480 != null) {
                    $myurl = $all_link->url_480;
                    $rest = substr($myurl, -4);
                    $type = null;
                    if ($rest == ".mp4") {
                        $type = 'video/mp4';

                    } elseif ($rest == "webm") {
                        $type = 'video/webm';

                    } elseif ($rest == 'm3u8') {
                        $type = 'application/x-mpegURL';

                    } elseif ($rest == '.3gp') {
                        $type = 'video/3gp';
                    } else {
                        $type = 'video/mp4';
                    }

                    $link = [
                        'src' => $all_link->url_480,
                        'type' => $type,
                        'label' => '480',
                        'res' => 480,
                    ];
                    $filterd_links->push($link);
                }
                if ($all_link->url_720 != null) {
                    $myurl = $all_link->url_720;
                    $rest = substr($myurl, -4);
                    $type = null;
                    if ($rest == ".mp4") {
                        $type = 'video/mp4';

                    } elseif ($rest == "webm") {
                        $type = 'video/webm';

                    } elseif ($rest == 'm3u8') {
                        $type = 'application/x-mpegURL';

                    } elseif ($rest == '.3gp') {
                        $type = 'video/3gp';

                    } else {
                        $type = 'video/mp4';
                    }

                    $link = [
                        'src' => $all_link->url_720,
                        'type' => $type,
                        'label' => '720',
                        'res' => 720,
                    ];
                    $filterd_links->push($link);
                }

                if ($all_link->url_1080 != null) {
                    $myurl = $all_link->url_1080;
                    $rest = substr($myurl, -4);
                    $type = null;
                    if ($rest == ".mp4") {
                        $type = 'video/mp4';

                    } elseif ($rest == "webm") {
                        $type = 'video/webm';

                    } elseif ($rest == 'm3u8') {
                        $type = 'application/x-mpegURL';

                    } elseif ($rest == '.3gp') {
                        $type = 'video/3gp';
                    } else {
                        $type = 'video/mp4';
                    }

                    $link = [
                        'src' => $all_link->url_1080,
                        'type' => $type,
                        'label' => '1080',
                        'res' => 1080,
                    ];
                    $filterd_links->push($link);
                }
            }

        } elseif ($type == 'S') {

            $season = Season::findOrFail($id);

            $all_data = collect();

            if (isset($season->episodes) && count($season->episodes) > 0) {
                foreach ($season->episodes as $key => $episode) {

                    $all_link = $episode->video_link;
                    $filterd_links = collect();

                    if ($all_link->ready_url != null) {
                        $myurl = $all_link->ready_url;
                        $rest = substr($myurl, 0, 14);
                        $rest2 = substr($myurl, 0, 20);
                        $type = null;
                        if ($rest == "https://youtu." || $rest2 == "https://www.youtube.") {
                            $type = 'video/youtube';
                        } elseif ($rest == 'https://vimeo.') {
                            $type = 'video/vimeo';
                        } else {
                            $type = 'video/mp4';
                        }

                        $link = [
                            'src' => $all_link->ready_url,
                            'type' => $type,
                        ];

                        $filterd_links->push($link);

                    } else {

                        if ($all_link->url_360 != null) {
                            $myurl = $all_link->url_360;
                            $rest = substr($myurl, -4);
                            $type = null;
                            if ($rest == ".mp4") {
                                $type = 'video/mp4';

                            } elseif ($rest == "webm") {
                                $type = 'video/webm';

                            } elseif ($rest == 'm3u8') {
                                $type = 'application/x-mpegURL';

                            } elseif ($rest == '.3gp') {
                                $type = 'video/3gp';
                            } else {
                                $type = 'video/mp4';
                            }

                            $link = [
                                'src' => $all_link->url_360,
                                'type' => $type,
                                'label' => '360',
                                'res' => 360,
                            ];
                            $filterd_links->push($link);
                        }
                        if ($all_link->url_480 != null) {
                            $myurl = $all_link->url_480;
                            $rest = substr($myurl, -4);
                            $type = null;
                            if ($rest == ".mp4") {
                                $type = 'video/mp4';

                            } elseif ($rest == "webm") {
                                $type = 'video/webm';

                            } elseif ($rest == 'm3u8') {
                                $type = 'application/x-mpegURL';

                            } elseif ($rest == '.3gp') {
                                $type = 'video/3gp';
                            } else {
                                $type = 'video/mp4';
                            }

                            $link = [
                                'src' => $all_link->url_480,
                                'type' => $type,
                                'label' => '480',
                                'res' => 480,
                            ];
                            $filterd_links->push($link);
                        }
                        if ($all_link->url_720 != null) {
                            $myurl = $all_link->url_720;
                            $rest = substr($myurl, -4);
                            $type = null;
                            if ($rest == ".mp4") {
                                $type = 'video/mp4';

                            } elseif ($rest == "webm") {
                                $type = 'video/webm';

                            } elseif ($rest == 'm3u8') {
                                $type = 'application/x-mpegURL';

                            } elseif ($rest == '.3gp') {
                                $type = 'video/3gp';

                            } else {
                                $type = 'video/mp4';
                            }

                            $link = [
                                'src' => $all_link->url_720,
                                'type' => $type,
                                'label' => '720',
                                'res' => 720,
                            ];
                            $filterd_links->push($link);
                        }

                        if ($all_link->url_1080 != null) {
                            $myurl = $all_link->url_1080;
                            $rest = substr($myurl, -4);
                            $type = null;
                            if ($rest == ".mp4") {
                                $type = 'video/mp4';

                            } elseif ($rest == "webm") {
                                $type = 'video/webm';

                            } elseif ($rest == 'm3u8') {
                                $type = 'application/x-mpegURL';

                            } elseif ($rest == '.3gp') {
                                $type = 'video/3gp';
                            } else {
                                $type = 'video/mp4';
                            }

                            $link = [
                                'src' => $all_link->url_1080,
                                'type' => $type,
                                'label' => '1080',
                                'res' => 1080,
                            ];
                            $filterd_links->push($link);
                        }
                    }

                    $data = [
                        "name" => $episode->title,
                        "sources" => $filterd_links,
                        "poster" => asset('images/tvseries/posters/' . ($season->poster != null ? $season->poster : $season->tvseries->poster)),
                        "thumbnail" => asset('images/tvseries/thumbnails/' . ($season->thumbnail != null ? $season->thumbnail : $season->tvseries->thumbnail)),
                    ];

                    $all_data->push($data);
                }
            }

        }

        return response()->json(['links' => $filterd_links, 'poster' => $poster, 'episode_data' => $all_data]);
    }
}
