<?php
return [
    // DEFAULT Target directory
	'directory' => public_path(),
    // For URL (e.g: http://base/media/filename.ext)
    'directory_name' => 'images',
    'url_prefix' => 'images',
    'prefix' => 'midia',
    // Multiple target directories
    'directories' => [
    	// Examples:
    	// ---------
    	// 'home' => [
    	// 	'path' => storage_path('media/home'),
    	// 	'name' => 'media/home' // as url prefix
    	// ],
    	'logo' => [
    		'path' => public_path().'/images/logo',
    		'name' => 'logo' // as url prefix
    	],
		'favicon' => [
    		'path' => public_path().'/images/favicon',
    		'name' => 'favicon' // as url prefix
    	],

		'livetvicon' => [
    		'path' => public_path().'/images/livetvicon',
    		'name' => 'livetvicon' // as url prefix
    	],

		'preloader' => [
    		'path' => public_path().'/images',
    		'name' => 'images' // as url prefix
    	],

		'movies_upload' => [
    		'path' => public_path().'/movies_upload',
    		'name' => 'movies_upload' // as url prefix
    	],
		'tvshow_upload' => [
    		'path' => public_path().'/tvshow_upload',
    		'name' => 'tvshow_upload' // as url prefix
    	],
		'movies_thumbnails'=>[
			'path' => public_path().'/images/movies/thumbnails',
    		'name' => 'movies/thumbnails' // as url prefix
		],
		'movies_posters'=>[
			'path' => public_path().'/images/movies/posters',
    		'name' => 'movies/posters' // as url prefix
		],
		'tvseries_thumbnails'=>[
			'path' => public_path().'/images/tvseries/thumbnails',
    		'name' => 'tvseries/thumbnails' // as url prefix
		],
		'tvseries_posters'=>[
			'path' => public_path().'/images/tvseries/posters',
    		'name' => 'tvseries/posters' // as url prefix
		],
		'season_thumbnails'=>[
			'path' => public_path().'/images/tvseries/thumbnails',
    		'name' => 'tvseries/thumbnails' // as url prefix
		],
		'episode_thumbnails'=>[
			'path' => public_path().'/images/tvseries/episodes',
    		'name' => 'tvseries/episodes' // as url prefix
		],
		'movie_url_360'=>[
			'path' => public_path().'/movies_upload/url_360',
    		'name' => 'movies_upload/url_360' // as url prefix
		],
		'movie_url_480'=>[
			'path' => public_path().'/movies_upload/url_480',
    		'name' => 'movies_upload/url_480' // as url prefix
		],
		'movie_url_720'=>[
			'path' => public_path().'/movies_upload/url_720',
    		'name' => 'movies_upload/url_720' // as url prefix
		],
		'movie_url_1080'=>[
			'path' => public_path().'/movies_upload/url_1080',
    		'name' => 'movies_upload/url_1080' // as url prefix
		],
		'tvseries_url_360'=>[
			'path' => public_path().'/movies_upload/url_360',
    		'name' => 'tvshow_upload/url_360' // as url prefix
		],
		'tvseries_url_480'=>[
			'path' => public_path().'/movies_upload/url_480',
    		'name' => 'tvshow_upload/url_480' // as url prefix
		],
		'tvseries_url_720'=>[
			'path' => public_path().'/movies_upload/url_720',
    		'name' => 'tvshow_upload/url_720' // as url prefix
		],
		'tvseries_url_1080'=>[
			'path' => public_path().'/movies_upload/url_1080',
    		'name' => 'tvshow_upload/url_1080' // as url prefix
		],

	

		// 'movies_thumbnail' => [
    	// 	'path' => public_path('images/movies/thumbnail'),
    	// 	'name' => 'movies_thumbnail' // as url prefix
    	// ],


    ],

    // Thumbnail size will be generated
	'thumbs' => [100/*, 80, 100*/],
];
