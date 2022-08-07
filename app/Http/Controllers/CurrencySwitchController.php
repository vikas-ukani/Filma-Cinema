<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CurrencySwitchController extends Controller
{
    public function index($currency){
        Session::put('current_currency', $currency);
        return back();
    }
    
}
