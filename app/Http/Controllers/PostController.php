<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function ban(Request $request, $tredId)
    {
        try{
            $postInstance = Post::findOrFail($tredId);

        }catch (ModelNotFoundException $e){
            return response('',404);
        }

        $isBanned = $postInstance->banners()->where('user_id',Auth::id())->get()->isNotEmpty();

        if(!$isBanned){
            $postInstance->banners()->attach(Auth::id());
            $response = response('',204);
        }else{
            $response = response('You have already baned this posts',406);
        }
        return $response;
    }

    public function postUp($postId)
    {
        try{
            $postInstance = Post::findOrFail($postId);
        }catch (ModelNotFoundException $e){
            return response('Этот пост не существует',404);
        }
        $postInstance->increment('to_top');
        return response('',204);
    }

    public function postDown($postId)
    {
        try{
            $postInstance = Post::findOrFail($postId);
        }catch (ModelNotFoundException $e){
            return response('Этот пост не существует',404);
        }
        $postInstance->increment('to_down');
        return response('',204);
    }
}
