<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'movies_upload' => [
            'driver' => 'local',
            'root' => public_path('movies_upload'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'movie_360' => [
            'driver' => 'local',
            'root' => public_path('movies_upload/movie_360'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'movie_480' => [
            'driver' => 'local',
            'root' => public_path('movies_upload/movie_480'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'movie_720' => [
            'driver' => 'local',
            'root' => public_path('movies_upload/movie_720'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'movie_1080' => [
            'driver' => 'local',
            'root' => public_path('movies_upload/movie_1080'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
         'tvshow_upload' => [
            'driver' => 'local',
            'root' => public_path('tvshow_upload'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'tv_360' => [
            'driver' => 'local',
            'root' => public_path('tvshow_upload/tv_360'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'tv_480' => [
            'driver' => 'local',
            'root' => public_path('tvshow_upload/tv_480'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'tv_720' => [
            'driver' => 'local',
            'root' => public_path('tvshow_upload/tv_720'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
          'tv_1080' => [
            'driver' => 'local',
            'root' => public_path('tvshow_upload/tv_1080'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'director_image_path' => [
            'driver' => 'local',
            'root' => public_path('images/directors'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'actor_image_path' => [
            'driver' => 'local',
            'root' => public_path('images/actors'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'imdb_poster_movie' => [
            'driver' => 'local',
            'root' => public_path('images/movies/thumbnails'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
         'imdb_poster_episode' => [
            'driver' => 'local',
            'root' => public_path('images/tvseries/episodes'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'imdb_backdrop_movie' => [
            'driver' => 'local',
            'root' => public_path('images/movies/posters'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'imdb_poster_tv_series' => [
            'driver' => 'local',
            'root' => public_path('images/tvseries/thumbnails'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'imdb_backdrop_tv_series' => [
            'driver' => 'local',
            'root' => public_path('images/tvseries/posters'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        
       's3' => [
        'driver' => 's3',
        'key'    => env('key'),
        'secret' => env('secret'),
        'region' => env('region'),
        'bucket' => env('bucket'),
        'url'    => env('url'),
    ],
      ],

];
