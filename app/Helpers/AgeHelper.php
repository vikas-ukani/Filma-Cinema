<?php

use App\Config;
use App\User;
use Illuminate\Support\Facades\Auth;

class AgeHelper
{
    public static function getage()
    {
        $age = 0;
        $config = Config::first();
        if ($config->age_restriction == 1) {
            if (Auth::user()) {
                # code...
                $user_id = Auth::user()->id;
                $user = User::findOrfail($user_id);
                $age = $user->age;
            } else {
                $age = 100;
            }
        }

        return $age;
    }
}
