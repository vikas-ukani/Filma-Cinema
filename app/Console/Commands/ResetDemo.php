<?php

namespace App\Console\Commands;

use App\Actor;
use App\AudioLanguage;
use App\CustomPage;
use App\Director;
use App\Episode;
use App\Genre;
use App\HomeBlock;
use App\Menu;
use App\MenuGenreShow;
use App\MenuSection;
use App\MenuVideo;
use App\Movie;
use App\Package;
use App\PackageFeature;
use App\PackageMenu;
use App\Season;
use App\TvSeries;
use App\Videolink;
use App\WatchHistory;
use App\Wishlist;
use Illuminate\Console\Command;

class ResetDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will reset your demo !';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            
            $this->info('Demo is resetting...');

            Menu::truncate();
            MenuGenreShow::truncate();
            MenuSection::truncate();
            MenuVideo::truncate();
            CustomPage::truncate();
            
            Package::truncate();
            PackageMenu::truncate();
            PackageFeature::truncate();

            $this->info('30% done...');

            HomeBlock::truncate();
            Movie::truncate();
            Episode::truncate();
            Season::truncate();
            TvSeries::truncate();
            Videolink::truncate();

            $this->info('50% done...');

            Wishlist::truncate();
            Actor::truncate();
            Director::truncate();
            Genre::truncate();
            AudioLanguage::truncate();
            WatchHistory::truncate();
            

            $leave_files = array('index.php');

            $dir0 = public_path() . '/images/movies/thumbnails';

            foreach (glob("$dir0/*") as $file) {

                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }

            }

            $dir1 = public_path() . '/images/movies/posters';

           

            foreach (glob("$dir1/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }

            }

            $dir = public_path() . '/images/movies/';

            

            foreach (glob("$dir/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }
            }

            $this->info('70% done...');

            $dir3 = public_path() . '/images/tvseries/thumbnails';

            

            foreach (glob("$dir3/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }
            }

            $dir4 = public_path() . '/images/tvseries/posters';

            

            foreach (glob("$dir4/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }
            }

            $dir5 = public_path() . '/images/episodes';

          
            foreach (glob("$dir5/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }
            }

            $dir6 = public_path() . '/images/genre';

           

            foreach (glob("$dir6/*") as $file) {
                if (!in_array(basename($file), $leave_files)) {
                    try {
                        unlink($file);
                    } catch (\Exception $e) {

                    }
                }
            }

          
            $this->info('100% done...');

            $this->info('Demo Reset Successfully !');
            \Artisan::call('key:generate');
        } catch (\Exception $e) {
            die('Database connection is not OK check .env file for more....');
        }
    }

    
}
