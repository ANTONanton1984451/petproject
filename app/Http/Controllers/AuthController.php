<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        $authUser = Auth::user();

        $authResponse = $authUser === null
                        ? response('',401)
                        : $authUser->only(['name','id']);

        return $authResponse;
    }
}
