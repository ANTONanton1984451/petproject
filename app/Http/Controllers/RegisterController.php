<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        //todo::Сделать регулярки!!!
        //todo::Поменять поле email на login
        $registerInputs = $request->only(['password','name','email']);

        $validator = Validator::make($registerInputs,
        [
            'password' => ['required','max:255'],
            'email'=>['required','max:15','string','unique:App\Models\User,email'],
            'name'=>['required','max:15','string','unique:App\Models\User,name']
        ]);

        if(!$validator->fails()){

            User::create([
                'name'=>$registerInputs['name'],
                'email'=>$registerInputs['email'],
                'password'=>Hash::make($registerInputs['password'])
            ]);

            if(Auth::attempt($request->only(['password','email']))){

                $response = response(['name'=>$registerInputs['name'],'id'=>Auth::id()]);
            }else{
                $response = response('cannot login',401);
            }

        }else{
           $response = response('validation fails',406);
        }

        return $response;
    }
}
