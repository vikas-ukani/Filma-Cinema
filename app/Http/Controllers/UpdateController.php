<?php

namespace App\Http\Controllers;

use App\Actor;
use App\Affilate;
use App\AppConfig;
use App\AppSlider;
use App\ChatSetting;
use App\ColorScheme;
use App\Config;
use App\CustomPage;
use App\Director;
use App\Label;
use App\Movie;
use App\PackageFeature;
use App\Season;
use App\SplashScreen;
use App\User;
use App\WalletSettings;
use App\Country;
use App\State;
use App\City;
use App\AppUiShorting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use ZipArchive;
use Illuminate\Support\Facades\Crypt;


class UpdateController extends Controller
{
    //Existing user
    public function exitterm()
    {
        return view('install.existeula');
    }

    public function updateeula(Request $request)
    {
        $d = \Request::getHost();
        $domain = str_replace("www.", "", $d);
        if (strstr($domain, 'localhost') || strstr($domain, '.test') || strstr($domain, 'mediacity.co.in') || strstr($domain, 'castleindia.in')) {
            $put = 1;
            file_put_contents(public_path() . '/config.txt', $put);
            return $this->process($request);
        } else {

            $request->validate([
                'eula' => 'required',
                'domain' => 'required',
                'code' => 'required',
            ],
                [
                    'eula.required' => __('Please accept Terms and Conditions !'),
                    'domain.required' => __('Please enter your domain name !'),
                    'code.required' => __('Please enter your envato purchase code !'),
                ]);

            $alldata = ['app_id' => "24626244", 'ip' => $request->ip(), 'domain' => $domain, 'code' => $request->code];

            $data = $this->make_request($alldata);

            if ($data['status'] == 1) {

                $put = 1;
                file_put_contents(public_path() . '/config.txt', $put);
                return $this->process($request);
            } elseif ($data['msg'] == 'Already Register') {

                return back()->withInput()->with('deleted', __('User is already registered'));
            } else {
                return back()->withInput()->with('deleted', $data['msg']);
            }

        }

    }

    public function process($request)
    {

        ini_set('memory_limit', '-1');

        try
        {
            DB::connection()
                ->getPdo();

            if (\Schema::hasTable('configs')) {

                try {

                    Artisan::call('migrate --path=database/migrations/existing');
                     /** version 3.1 Code */
                    Artisan::call('migrate --path=database/migrations/update_3_1');
                     /** version 3.2 Code */
                    Artisan::call('migrate --path=database/migrations/update_v3_2');
                     /** version 3.3 Code */
                    Artisan::call('migrate --path=database/migrations/update_v3_3');
                     /** version 3.4 Code */
                    Artisan::call('migrate --path=database/migrations/update_v3_4');
                     /** version 3.5 Code */
                    Artisan::call('migrate --path=database/migrations/update_v4_0');


                     /** version 4.1 Code */
                    if (config('app.version') == '4.1') {
                        $this->UpdateToVersion4_1();

                    }

                    /** version 4.2 Code */
                   
                        $this->UpdateToVersion4_2();
                     /** version end 4.2 Code */

                     /** version  4.3 Code */
                        $this->UpdateToVersion4_3();
                    /** version end 4.3 Code */

                    /** version  4.4 Code */
                    $this->UpdateToVersion4_4();
                    /** version end 4.4 Code */

                    if (\Schema::hasTable('chat_settngs')) {
                        $chat_setting = ChatSetting::first();
                        if (!isset($chat_setting)) {
                            Artisan::call('db:seed', ['--class' => 'ChatSettingsTableSeeder']);
                        }
                    }
                    if (\Schema::hasTable('app_sliders')) {
                        $app_slider = AppSlider::first();
                        if (!isset($app_slider)) {
                            Artisan::call('db:seed', ['--class' => 'AppSlidersTableSeeder']);
                        }
                    }
                    if (\Schema::hasTable('app_configs')) {
                        $app_config = AppConfig::first();
                        if (!isset($app_config)) {
                            Artisan::call('db:seed', ['--class' => 'AppConfigsTableSeeder']);
                        }
                    }
                    if (\Schema::hasTable('splash_screens')) {
                        $splashscreen = SplashScreen::first();
                        if (!isset($splashscreen)) {
                            Artisan::call('db:seed', ['--class' => 'SplashScreensTableSeeder']);
                        }
                    }

                    if (\Schema::hasTable('oauth_clients')) {
                        $outhclient = DB::table('oauth_clients')->get();
                        if (!isset($outhclient)) {
                            Artisan::call('db:seed', ['--class' => 'OauthClientsTableSeeder']);
                        }
                    }

                    if (\Schema::hasTable('oauth_personal_access_clients')) {
                        $outhpersonalaccess = DB::table('oauth_personal_access_clients')->first();
                        if (!isset($outhpersonalaccess)) {
                            Artisan::call('db:seed', ['--class' => 'OauthPersonalAccessClientsTableSeeder']);
                        }
                    }

                    if (\Schema::hasTable('color_schemes')) {
                        $color_setting = ColorScheme::first();
                        if (!isset($color_setting)) {
                            Artisan::call('db:seed', ['--class' => 'ColorSchemesTableSeeder']);
                        }
                    }

                    $movies = Movie::where('slug', '=', null)->get();
                    if (isset($movies) && count($movies) > 0) {
                        foreach ($movies as $movie) {
                            $m = Movie::find($movie->id);
                            if (isset($m)) {
                                $m->slug = str_slug($m->title, '-');
                                $m->slug;
                                $m->save();
                            }

                        }
                    }

                    $seasons = Season::where('season_slug', '=', null)->get();
                    if (isset($seasons) && count($seasons) > 0) {
                        foreach ($seasons as $season) {
                            $s = Season::find($season->id);
                            if (isset($s)) {
                                $s->season_slug = str_slug($s->tvseries->title . '-season-' . $s->season_no, '-');
                                $s->save();
                            }

                        }
                    }

                    $this->changeEnv(['IS_INSTALLED' => '1', 'APP_DEBUG' => 'false']);
                    if (!file_exists(storage_path() . '/app/keys/license.json')) {

                        /** License Migration Process */

                        $token = @file_get_contents(public_path() . '/intialize.txt');
                        $code = @file_get_contents(public_path() . '/code.txt');
                        $domain = @file_get_contents(public_path() . '/ddtl.txt');

                        if ($token != '' && $code != '') {

                            $lic_json = array(

                                'name' => env('APP_NAME'),
                                'code' => $code,
                                'type' => __('envato'),
                                'domain' => $domain,
                                'lic_type' => __('regular'),
                                'token' => $token,

                            );

                            $file = json_encode($lic_json);

                            $filename = 'license.json';

                            Storage::disk('local')->put('/keys/' . $filename, $file);

                            /** Delete this token files */

                            try {

                                $token ? unlink(public_path() . '/intialize.txt') : '';
                                $code ? unlink(public_path() . '/code.txt') : '';
                                $domain ? unlink(public_path() . '/ddtl.txt') : '';

                            } catch (\Exception $e) {
                                Log::error('Failed to migrate license reason : ' . $e->getMessage());
                            }

                        }

                    }

                    return redirect('/')->with('added', __('Updated Successfully'));
                } catch (\Exception $e) {

                    return back()->withInput()->with('deleted', $e->getMessage());
                }

            }

        } catch (\Exception $e) {

            return redirect()->route('existterm')->withInput()->with('deleted', $e->getMessage());

        }
    }

    public function UpdateToVersion4_1()
    {

        Artisan::call('migrate --path=database/migrations/update_v4_1');

        if (Role::count() < 1) {
            Artisan::call('db:seed --class=RolesTableSeeder');
        }

        if (Permission::count() < 1) {
            Artisan::call('db:seed --class=PermissionsTableSeeder');
        }

        if (DB::table('role_has_permissions')->count() < 1) {
            Artisan::call('db:seed --class=RoleHasPermissionsTableSeeder');
        }

        if (DB::table('currencies')->count() < 1) {
            Artisan::call('db:seed --class=CurrenciesTableSeeder');
        }

        if (env('ACL_UPGRADE') == 0) {

            $users = User::get();

            $users->each(function ($user) {

                if ($user->is_admin == 1) {

                    $user->assignRole('Super Admin');

                }

                if ($user->is_assistant == 1) {

                    $user->assignRole('Producer');

                }

                if ($user->is_admin == 0 && $user->is_assistant == 0) {

                    $user->assignRole('User');

                }

            });

            $acl_status = DotenvEditor::setKeys([
                'ACL_UPGRADE' => '1',
            ]);

            $acl_status->save();

            if(env('ACL_UPGRADE') == 0){
                $actors = DB::table('actors')->get();
                if (isset($actors) && count($actors) > 0) {
                    foreach ($actors as $actor) {
                        $updateActor = Actor::where('id', $actor->id)->first();
                        if (isset($updateActor)) {
                            $updateActor->slug = str_slug($actor->name, '-');
                            $updateActor->name = array('en' => $actor->name);
                            $updateActor->biography = array('en' => $actor->biography);
                            $updateActor->save();
                        }
                    }
                }
                $directors = DB::table('directors')->get();
                if (isset($directors) && count($directors) > 0) {
                    foreach ($directors as $director) {
                        $updateDirector = Director::where('id', $director->id)->first();
                        if (isset($updateDirector)) {
                            $updateDirector->slug = str_slug($director->name, '-');
                            $updateDirector->name = array('en' => $director->name);
                            $updateDirector->biography = array('en' => $director->biography);
                            $updateDirector->save();
                        }
                    }
                }

                $labels = DB::table('labels')->get();
                if (isset($labels) && count($labels) > 0) {
                    foreach ($labels as $label) {
                        $updateLabel = Label::where('id', $label->id)->first();
                        if (isset($updateLabel)) {
                            $updateLabel->name = array('en' => $label->name);
                            $updateLabel->save();
                        }
                    }
                }

                $packageFeatures = DB::table('package_features')->get();
                if (isset($packageFeatures) && count($packageFeatures) > 0) {
                    foreach ($packageFeatures as $pf) {
                        $updateFeatures = PackageFeature::where('id', $pf->id)->first();
                        if (isset($updateFeatures)) {
                            $updateFeatures->name = array('en' => $pf->name);
                            $updateFeatures->save();
                        }
                    }
                }

                $pages = DB::table('custom_pages')->get();
                if (isset($pages) && count($pages) > 0) {
                    foreach ($pages as $page) {
                        $updatePage = CustomPage::where('id', $page->id)->first();
                        if (isset($updatePage)) {
                            $updatePage->title = array('en' => $page->title);
                            $updatePage->detail = array('en' => $page->detail);
                            $updatePage->save();
                        }
                    }
                }
            }

            
        }

    }

    public function UpdateToVersion4_2()
    {
        Log::error("update version 4.2 update start");
        try {
            Artisan::call('migrate --path=database/migrations/update_v4_2');

            if (WalletSettings::count() < 1) {
                Artisan::call('db:seed --class=WalletSettingsTableSeeder');
            }

            if (Affilate::count() < 1) {
                Artisan::call('db:seed --class=AffilatesTableSeeder');
            }

            if (Permission::count() < 1) {
                Artisan::call('db:seed --class=UpdatePermissions');
            }

            Log::info('nexthour version 4.2 Update End');

        } catch (\Exception $e) {
            Log::error("update version 4.2 ERROR:" . $e->getMessage());
        }

    }

    public function UpdateToVersion4_3()
    {
        Log::error("update version 4.3 update start");
        try {
            Artisan::call('migrate --path=database/migrations/update_v4_3');

            $movies = Movie::get();

            if(isset($movies)){
                $movies->each(function ($movie) {

                    if ($movie->password != NULL) {
    
                        $moviePassowrd =$movie->password;
                       
                        $stringLength = strlen($moviePassowrd);
                       
                        if($stringLength < 100){
                            
                           $encrypted =  Crypt::encrypt($moviePassowrd);
                            
                           $movie->update([
                            'password' => $encrypted,
                           ]);
                          
                        }
                    }
    
                   
    
                });
            }

           $seasons =  Season::get();
           if(isset($seasons)){
                $seasons->each(function ($season) {

                    if ($season->password != NULL) {

                        $seasonPassowrd =$season->password;
                    
                        $stringLength = strlen($seasonPassowrd);
                    
                        if($stringLength < 100){
                            
                        $encrypted =  Crypt::encrypt($seasonPassowrd);
                            
                        $season->update([
                            'password' => $encrypted,
                        ]);
                        
                        }
                    }

                

                });
            }
            

            Log::info('nexthour version 4.3 Update End');

        } catch (\Exception $e) {
            Log::error("update version 4.3 ERROR:" . $e->getMessage());
        }

    }

    public function UpdateToVersion4_4()
    {
        Log::error("update version 4.4 update start");
        try {
            Artisan::call('migrate --path=database/migrations/update_v4_4');

            if (Country::count() < 1) {
                Artisan::call('db:seed --class=CountriesTableSeeder');
            }

            if (State::count() < 1) {
                Artisan::call('db:seed --class=StatesTableSeeder');
            }

            if (City::count() < 1) {
                Artisan::call('db:seed --class=CitiesTableSeeder');
            }

            if (AppUiShorting::count() < 1) {
                Artisan::call('db:seed --class=AppUiShortingsTableSeeder');
            }

            Log::info('nexthour version 4.4 Update End');

        } catch (\Exception $e) {
            Log::error("update version 4.4 ERROR:" . $e->getMessage());
        }

    }

    public function make_request($alldata)
    {
        $response = Http::post('https://mediacity.co.in/purchase/public/api/verifycode', [
            'app_id' => $alldata['app_id'],
            'ip' => $alldata['ip'],
            'code' => $alldata['code'],
            'domain' => $alldata['domain'],
        ]);

        $result = $response->json();

        if ($response->successful()) {
            if ($result['status'] == '1') {
                $file = public_path() . '/intialize.txt';
                file_put_contents($file, $result['token']);
                file_put_contents(public_path() . '/code.txt', $alldata['code']);
                file_put_contents(public_path() . '/ddtl.txt', $alldata['domain']);
                return array(
                    'msg' => $result['message'],
                    'status' => '1',
                );
            } else {
                $message = $result['message'];
                return array(
                    'msg' => $message,
                    'status' => '0',
                );
            }
        } else {
            $message = "Failed to validate";
            return array(
                'msg' => $message,
                'status' => '0',
            );
        }

    }

    protected function changeEnv($data = array())
    {
        {
            if (count($data) > 0) {

                // Read .env-file
                $env = file_get_contents(base_path() . '/.env');

                // Split string on every " " and write into array
                $env = preg_split('/\s+/', $env);

                // Loop through given data
                foreach ((array) $data as $key => $value) {
                    // Loop through .env-data
                    foreach ($env as $env_key => $env_value) {
                        // Turn the value into an array and stop after the first split
                        // So it's not possible to split e.g. the App-Key by accident
                        $entry = explode("=", $env_value, 2);

                        // Check, if new key fits the actual .env-key
                        if ($entry[0] == $key) {
                            // If yes, overwrite it with the new one
                            $env[$env_key] = $key . "=" . $value;
                        } else {
                            // If not, keep the old one
                            $env[$env_key] = $env_value;
                        }
                    }
                }

                // Turn the array back to an String
                $env = implode("\n\n", $env);

                // And overwrite the .env with the new data
                file_put_contents(base_path() . '/.env', $env);

                return true;

            } else {

                return false;
            }
        }
    }

    public function mergeQuickupdate(Request $request)
    {
        $file = @file_get_contents(config('app.ota_url') . config('app.version') . '/' . $request->filename);

        if (!$file) {
            notify()->error(__('Update file not found !'), '404');
            return back();
        }

        $version = $request->version;

        Storage::disk('local')->put('/bugfixer/' . $request->filename, $file);

        $file = storage_path() . '/app/bugfixer/' . $request->filename;

        $zip = new ZipArchive;

        $zipped = $zip->open($file, ZIPARCHIVE::CREATE);

        if ($zipped) {

            $extract = $zip->extractTo(base_path());

            if ($extract) {

                $version_json = array(

                    'version' => config('app.version'),
                    'subversion' => $version,

                );

                $version_json = json_encode($version_json);

                $filename = 'version.json';

                $zip->close();

                Storage::disk('local')->put('/bugfixer/' . $filename, $version_json);

                Storage::delete('/app/bugfixer/' . $request->filename);

                return back()->with('added', __('Quick Hot fix update has been merged successfully !'));
            }

        }

    }
    public function checkforupate(Request $request)
    {

        if ($request->ajax()) {

            $version = @file_get_contents(storage_path() . '/app/bugfixer/version.json');

            $version = json_decode($version, true);

            $current_version = $version['version'];

            $current_subversion = $version['subversion'];

            $new_version = str_replace('.', '', $current_subversion) + 1;
            $new_version = implode('.', str_split($new_version));

            $repo = @file_get_contents(config('app.ota_url') . $current_version . '/' . $new_version . '.json');

            if ($repo != '') {

                $repo = json_decode($repo);

                return response()->json([
                    'status' => 'update_avbl',
                    'msg' => __('Update available'),
                    'version' => $repo->subversion,
                    'filename' => $repo->filename,
                ]);

            } else {

                return response()->json([
                    'status' => 'uptodate',
                    'msg' => __('Your application is up to date'),
                ]);
            }

        }

    }
}
