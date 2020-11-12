<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function deleteProfile(Request $request)
    {

        $user  = Auth::user();
        Auth::logout();

        try{
            $user->delete();
        }catch (\Exception $e){
            return response('Что-то пошло не так',501);
        }

        return response('OK');
    }
    //todo::Регулярки!!!!
    public function changeNickName(Request $request)
    {
        $nameInput = $request->get('name');
        $validator = Validator::make(['name'=>$nameInput],[
           'name'=>['required','max:15','string','unique:App\Models\User,name']
        ]);

        if(!$validator->fails()){
            $authUser = Auth::user();
            $authUser->name = $nameInput;
            try{
                $authUser->save();
                $response = response(['name'=>$nameInput]);
            }catch (\Exception $e){
                return response('Проблемы с сервером,попробуйте снова',501);
            }
        }else{
                $response = response('Неверный формат',406);
        }

        return $response;
    }

    public function changePassword(Request $request)
    {
        $passwordInput = $request->get('password');
        $validator = Validator::make(['password'=>$passwordInput],[
            'password' => ['required','max:255']
        ]);

        if(!$validator->fails()){
            $authUser = Auth::user();
            $currentPassword = $authUser->password;

            if(!Hash::check($passwordInput,$currentPassword)){
                $authUser->password = Hash::make($passwordInput);

                try{
                    $authUser->save();
                    $response = response('Ок');
                }catch (\Exception $e){
                    return response('Проблемы с сервером,попробуйте снова',501);
                }

            }else{
                $response = response('Этот пароль уже установлен',406);
            }
        }else{
            $response = response('Неверный формат пароля',406);
        }
        return $response;
    }
}
