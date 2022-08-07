<?php

namespace App\Http\Controllers;

use Spatie\Sitemap\SitemapGenerator;


class SiteMapController extends Controller
{
    public function sitemapGenerator(){

        SitemapGenerator::create(url('/'))->writeToFile('sitemap.xml');
        return back()->with('added','Sitemap generated successfully !');

    }

    public function download(){


        return response()->download(public_path('/sitemap.xml'));


    }
}
