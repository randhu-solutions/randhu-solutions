<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Usersession;

class TokenController extends Controller
{
    public static function getUserbyToken($token)
    {
        $user = Usersession::where('token_session',$token)->first();
        return $user;
    }
}
