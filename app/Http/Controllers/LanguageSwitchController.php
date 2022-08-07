<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;


class LanguageSwitchController extends Controller
{
   
    public function languageSwitch($local)
    {

        Session::put('changed_language', $local);
        return back();
    }
}
