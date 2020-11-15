<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    public function create(Request $request)
    {
       $createCommunityInputs = $request->only(['name','rules','description']);
       //todo::Регулярки!!!!
       $validator = Validator::make($createCommunityInputs,[
           'name'=>['required','max:15','string','unique:App\Models\Community,name'],
           'rules'=>['required','max:500','string'],
           'description'=>['required','max:500','string']
       ]);

       if(!$validator->fails()){
          try{
              $community = Community::create([
                  'name'=>$createCommunityInputs['name'],
                  'rules'=>$createCommunityInputs['rules'],
                  'description'=>$createCommunityInputs['description'],
                  'user_id'=>Auth::id()
              ]);
          }catch (\Exception $e){
             return response('Проблемы с сервером,попробуйте снова',501);
          }

          $response = response(['id'=>$community->id]);

       }else{
          $response = response('validation fails',406);
       }
          return $response;
    }

    public function join($communityId)
    {
        try{
            $communityInstance = Community::findOrFail($communityId);
        }catch (ModelNotFoundException $e){
            return response('',404);
        }
        $isAlreadyJoin = $communityInstance->isJoin(Auth::id());
        if(!$isAlreadyJoin){
            $communityInstance->subscribers()->attach(Auth::id());
            $communityInstance->increment('subscribers');
            $response = response('',204);
        }else{
            $response = response('You have already join',406);
        }
        return $response;
    }

    public function getPopular()
    {
        return Community::popular()->limit(5)->get()->toArray();
    }
}
