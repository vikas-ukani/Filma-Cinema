<?php

namespace App\Console\Commands;

use App\Package;
use App\Wishlist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class ImportDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will import demo on your script !';

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
        $this->info('Importing Demo...');

        
        Wishlist::truncate();
        Package::truncate();

        Artisan::call('db:seed --class=MenusTableSeeder');
        Artisan::call('db:seed --class=MenuGenreShowsTableSeeder');
        Artisan::call('db:seed --class=MenuSectionsTableSeeder');
        Artisan::call('db:seed --class=CustomPagesTableSeeder');

        Artisan::call('db:seed --class=PackageFeaturesTableSeeder');
        Artisan::call('db:seed --class=PackagesTableSeeder');
        Artisan::call('db:seed --class=PackageMenuTableSeeder');

        $this->info('30% done...');

        Artisan::call('db:seed --class=ActorsTableSeeder');
        Artisan::call('db:seed --class=DirectorsTableSeeder');
        Artisan::call('db:seed --class=GenresTableSeeder');
        Artisan::call('db:seed --class=AudioLanguagesTableSeeder');

        $this->info('50% done...');
        
        Artisan::call('db:seed --class=MoviesTableSeeder');
        Artisan::call('db:seed --class=MenuVideosTableSeeder');
        Artisan::call('db:seed --class=VideolinksTableSeeder');
        Artisan::call('db:seed --class=TvSeriesTableSeeder');
        Artisan::call('db:seed --class=SeasonsTableSeeder');
        Artisan::call('db:seed --class=EpisodesTableSeeder');
        Artisan::call('db:seed --class=HomeBlocksTableSeeder');

        $this->info('70% done...');

        Artisan::call('db:seed --class=WatchHistoriesTableSeeder');
        Artisan::call('db:seed --class=WishlistsTableSeeder');


        ini_set('max_execution_time', 200);

        $file = public_path().'/democontent.zip'; 
        
        $this->info('Extracting demo contents...');

        try{
                //create an instance of ZipArchive Class
            $zip = new ZipArchive;
             
            //open the file that you want to unzip. 
            //NOTE: give the correct path. In this example zip file is in the same folder
            $zipped = $zip->open($file);
             
            // get the absolute path to $file, where the files has to be unzipped
            $path = public_path();
             
            //check if it is actually a Zip file
            if ($zipped) {
                //if yes then extract it to the said folder
              $extract = $zip->extractTo($path);

              //close the zip
              $zip->close();  
             
              //if unzipped succesfully then show the success message
              if($extract){
                 //$this->info('Demo data imported successfully !');
                 Artisan::call('key:generate');
              }
            }
        }catch(\Exception $e){
            die($e->getMessage());
        }
    }
}
