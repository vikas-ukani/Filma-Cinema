<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;


class BackupController extends Controller
{
   

    public function __construct()
    {
        $this->middleware('permission:help.db-backup', ['only' => ['get', 'updatepath', 'process', 'download']]);
        $this->middleware('permission:help.remove-public', ['only' => ['getremove_public', 'remove_public']]);
        $this->middleware('permission:help.system-status', ['only' => ['system_status']]);
        $this->middleware('permission:help.clear-cache', ['only' => ['clearcahe']]);
    }

    public function get()
    {

        Artisan::call('backup:list');

        $html = '<pre>';
        $html .= Artisan::output();
        $html .= '</pre>';

        return view('admin.backup.index', compact('html'));

    }
    public function updatepath(Request $request)
    {
        $env_update = DotenvEditor::setKeys(['BINARY_PATH' => $request->BINARY_PATH]);
        return back()->with('added', __('Binary Path Successfully added !'));
    }

    public function process(Request $request)
    {

        if (env('DEMO_LOCK') == 1) {

            return back()->with('deleted', __('This action is disabled in demo!'));
        }

        set_time_limit(0);

        if ($request->type == 'all') {
            Artisan::call('backup:run');
        }

        if ($request->type == 'onlyfiles') {

            Artisan::call('backup:run --only-files');

        }

        if ($request->type == 'onlydb') {

            Artisan::call('backup:run --only-db');

        }

        return back()->with('added', 'Backup completed !', 'Done');

    }

    public function download(Request $request, $filename)
    {

        if (env('DEMO_LOCK') == 1) {

            return back()->with('delete', __('This action is disabled in demo !'));
        }

        if (!$request->hasValidSignature()) {

            return redirect(route('admin.backup.settings'))->with('delete', __('Download Link is invalid or expired !'));
        }

        $filePath = storage_path() . '/app/' . config('app.name') . '/' . $filename;

        $fileContent = file_get_contents($filePath);

        $response = response($fileContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

        return $response;

    }

    public function getremove_public()
    {
        return view('admin.removepublic');
    }

    public function remove_public()
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }
        $getstatus = @file_get_contents('../.htaccess');
        if (!$getstatus) {
            $code = '<IfModule mod_rewrite.c>
                RewriteEngine On
                RewriteRule ^(.*)$ public/$1 [L]
              </IfModule>';
            @file_put_contents('../.htaccess', $code);
            return back()->with('added', __('Remove public from URL Successfully!'));
        } else {
            return back()->with('updated', __('Already Remove public from URL!'));
        }

    }

    public function clearcahe()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:cache');
            Artisan::call('view:clear');
            return back()->with('added', __('Clear cache Successfully!'));
        } catch (\Exception $e) {
            return back()->with('deleted', $e->getMessage());
        }
    }

    public function system_status()
    {
        return view('admin.systemstatus');
    }
}
