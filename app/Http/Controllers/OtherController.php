<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

  
class OtherController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:help.import-demo', ['only' => ['getImportDemo', 'ImportDemo', 'DemoReset']]);
    }

    public function getImportDemo()
    {
        return view('admin.import-demo');
    }

    public function ImportDemo()
    {
        if (env('DEMO_LOCK') == 1) {

            return back()->with('deleted', __('This action is disabled in demo !'));
        }

        Artisan::call('import:demo');

        return back()->with('added', __('Demo Imported successfully !'));
    }

    public function DemoReset()
    {
        if (env('DEMO_LOCK') == 1) {

            return back()->with('deleted', __('This action is disabled in demo !'));
        }

        Artisan::call('reset:demo');
        return back()->with('added', __('Demo reset successfully !'));
    }
}
