<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    private const AUTH_ERROR_MESSAGE = 'Неверный пароль или логин';

    public function login(Request $request)
    {
        $loginInputs = $request->only(['password','email']);

        if(Auth::attempt($loginInputs)){

            $authenticatedUser = Auth::user()->only(['name','id']);
            $response = $authenticatedUser;

        }else{

            $response = response(self::AUTH_ERROR_MESSAGE,401);
        }
        return $response;
    }

    public function logOut()
    {
        //todo:: Try|Catch????
        Auth::logout();
        Session::flush();
        return 'Ok';
    }
}
