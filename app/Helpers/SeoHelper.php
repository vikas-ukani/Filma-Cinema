<?php

namespace App\Helpers;

use App\Config;
use App\seo;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class SeoHelper
{

    public static function settings()
    {

        try {

            $seo = seo::first();
            $setting = Config::first();

            SEOTools::setDescription($seo->description);
            SEOMeta::addKeyword([$seo->keyword]);
            SEOTools::opengraph()->setUrl(url('/'));
            SEOTools::setCanonical(url('/'));
            SEOTools::opengraph()->addProperty('type', 'Nexthour Subscription portal');
            SEOTools::twitter()->setSite(url('/'));
            SEOTools::jsonLd()->addImage(url('/images/logo/' . $setting->logo));
            OpenGraph::addImage(url('/images/logo/' . $setting->logo));

            SEOMeta::setRobots('all');

        } catch (\Exception $e) {

        }

    }

}
