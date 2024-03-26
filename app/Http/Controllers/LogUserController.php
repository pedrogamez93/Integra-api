<?php

namespace App\Http\Controllers;
use App\UserLog;

class LogUserController extends Controller
{
    public function store($module)
    {
        $userdata = auth('api')->user();
        $user = new UserLog();
        $user->module = $module;
        $user->user_id = $userdata ? $userdata->id : 0;
        $user->save(); 
        return $user;
    }
}
