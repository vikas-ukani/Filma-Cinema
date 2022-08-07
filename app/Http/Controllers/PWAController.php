<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;



class PWAController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:site-settings.pwa', ['only' => ['index', 'updatesetting', 'updateicons']]);
    }

    public function index()
    {

        return view('admin.pwa.index');
    }

    public function updatesetting(Request $request)
    {

        $request->validate([
            'shorticon_1' => 'mimes:png|max:2000',
            'shorticon_2_' => 'mimes:png|max:2000',
        ]);

        $env_keys_save = DotenvEditor::setKeys([
            'PWA_NAME' => $request->app_name,
            'PWA_ENABLE' => isset($request->PWA_ENABLE) ? "1" : "0",
            'PWA_BG_COLOR' => $request->PWA_BG_COLOR,
            'PWA_THEME_COLOR' => $request->PWA_THEME_COLOR,
        ]);

        $input = $request->all();

        $input['app_name'] = $request->app_name;

        $input['start_url'] = url('/');

        $destinationPath = public_path('/images/icons');

        if ($request->file('shorticon_1')) {

            $image = $request->file('shorticon_1');

            $short_icon1 = 'shorticon_1_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->path());

            $img->resize(96, 96);

            $img->save($destinationPath . '/' . $short_icon1, 90);

            $app_settings = DotenvEditor::setKeys([
                'SHORTCUT_ICON1' => $short_icon1,
            ]);

            $app_settings->save();

        }

        if ($request->file('shorticon_2')) {

            $image = $request->file('shorticon_2');

            $short_icon2 = 'shorticon_2_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->path());

            $img->resize(96, 96);

            $img->save($destinationPath . '/' . $short_icon2, 90);

            $app_settings = DotenvEditor::setKeys([
                'SHORTCUT_ICON2' => $short_icon2,
            ]);

            $app_settings->save();

        }

        $env_keys_save->save();

        Artisan::call('view:cache');
        Artisan::call('view:clear');

        return back()->with('updated', __('PWA App Setting Updated !'));

    }

    public function updateicons(Request $request)
    {

        $request->validate([
            'icon_512' => 'mimes:png|max:2000',
            'splash_2048' => 'mimes:png|max:2000',
        ]);

        $destinationPath = public_path('/images/icons');

        if ($request->file('icon_512')) {

            ini_set('max_execution_time', -1);

            $image = $request->file('icon_512');

            $img = Image::make($image->path());

            // 512 x 512

            $icon512 = 'icon-512x512.' . $image->getClientOriginalExtension();

            $img->resize(512, 512);

            $img->save($destinationPath . '/' . $icon512, 90);

            // 256x256

            $icon384 = 'icon-384x384.' . $image->getClientOriginalExtension();

            $img->resize(384, 384);

            $img->save($destinationPath . '/' . $icon384, 90);

            // 192x192

            $icon192 = 'icon-192x192.' . $image->getClientOriginalExtension();

            $img->resize(192, 192);

            $img->save($destinationPath . '/' . $icon192, 90);

            // 152x152

            $icon152 = 'icon-152x152.' . $image->getClientOriginalExtension();

            $img->resize(152, 152);

            $img->save($destinationPath . '/' . $icon152, 90);

            // 144x144

            $icon144 = 'icon-144x144.' . $image->getClientOriginalExtension();

            $img->resize(144, 144);

            $img->save($destinationPath . '/' . $icon144, 90);

            // 128x128

            $icon128 = 'icon-128x128.' . $image->getClientOriginalExtension();

            $img->resize(128, 128);

            $img->save($destinationPath . '/' . $icon128, 90);

            // 96x96

            $icon96 = 'icon-96x96.' . $image->getClientOriginalExtension();

            $img->resize(96, 96);

            $img->save($destinationPath . '/' . $icon96, 90);

            // 72x72

            $icon72 = 'icon-72x72.' . $image->getClientOriginalExtension();

            $img->resize(72, 72);

            $img->save($destinationPath . '/' . $icon72, 90);

        }

        /** Splash Screens */

        /** 2048x2732 */

        if ($file = $request->file('splash_2048')) {

            ini_set('max_execution_time', -1);

            $image = $request->file('splash_2048');

            $img = Image::make($image->path());

            // 2048x2732

            $splash2732 = 'splash-2048x2732.' . $image->getClientOriginalExtension();

            $img->resize(2048, 2732);

            $img->save($destinationPath . '/' . $splash2732, 95);

            // 1668x2388

            $splash2388 = 'splash-1668x2388.' . $image->getClientOriginalExtension();

            $img->resize(1668, 2388);

            $img->save($destinationPath . '/' . $splash2388, 95);

            // 1668x2224

            $splash2224 = 'splash-1668x2224.' . $image->getClientOriginalExtension();

            $img->resize(1668, 2224);

            $img->save($destinationPath . '/' . $splash2224, 95);

            // 1536x2048

            $splash2048 = 'splash-1536x2048.' . $image->getClientOriginalExtension();

            $img->resize(1536, 2048);

            $img->save($destinationPath . '/' . $splash2048, 95);

            // 1242x2688

            $splash2688 = 'splash-1242x2688.' . $image->getClientOriginalExtension();

            $img->resize(1242, 2688);

            $img->save($destinationPath . '/' . $splash2688, 95);

            // 1242x2208

            $splash2208 = 'splash-1242x2208.' . $image->getClientOriginalExtension();

            $img->resize(1242, 2208);

            $img->save($destinationPath . '/' . $splash2208, 95);

            // 1125x2436

            $splash2436 = 'splash-1125x2436.' . $image->getClientOriginalExtension();

            $img->resize(1125, 2436);

            $img->save($destinationPath . '/' . $splash2436, 95);

            // 828x1792

            $splash1792 = 'splash-828x1792.' . $image->getClientOriginalExtension();

            $img->resize(828, 1792);

            $img->save($destinationPath . '/' . $splash1792, 95);

            // 750x1334

            $splash1334 = 'splash-750x1334.' . $image->getClientOriginalExtension();

            $img->resize(750, 1334);

            $img->save($destinationPath . '/' . $splash1334, 95);

            // 640x1136

            $splash1136 = 'splash-640x1136.' . $image->getClientOriginalExtension();

            $img->resize(640, 1136);

            $img->save($destinationPath . '/' . $splash1136, 95);

        }

        Artisan::call('view:cache');
        Artisan::call('view:clear');

        return back()->with('updated', __('Icons are updated Successfully'));
    }
}
