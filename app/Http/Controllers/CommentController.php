<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function getTredComments(Request $request,$tredId)
    {
        $sortMethod = $request->query('sort');

        $validator = Validator::make(['sort_method'=>$sortMethod],[
            'sort_method'=>Rule::in(['new','top','old'])
        ]);

        if(!$validator->fails()){
            $comments = Comment::$sortMethod(+$tredId)->simplePaginate(5)->withQueryString();
        }else{
            $comments = 'Неверный метод сортировки';
        }

        return response($comments);
    }

    public function commentUp($commentId)
    {
        try{
            $commentInstance = Comment::findOrFail($commentId);
        }catch (ModelNotFoundException $e){
            return response('Данного коментария не существует',404);
        }
        $commentInstance->increment('to_top');
        return response('',204);
    }

    public function commentDown($commentId)
    {
        try{
            $commentInstance = Comment::findOrFail($commentId);
        }catch (ModelNotFoundException $e){
            return response('Данного коментария не существует',404);
        }
        $commentInstance->increment('to_down');
        return response('',204);
    }

    public function addComment(Request $request,$tredId)
    {
        if(!Comment::alreadyHas(Auth::id(),$tredId)){

            $validator = Validator::make($request->only('text'),[
                'text'=>['required','min:0','max:150']
            ]);

            if(!$validator->fails()){
               try{
                   $commentInstance = Comment::create([
                       'text'=>$request->get('text'),
                       'user_id'=>Auth::id(),
                       'post_id'=>$tredId
                   ]);

                   $commentInstance->user;
               }catch (QueryException $e){
                   return response('post not found',404);
               }

               $response = response($commentInstance->toArray());
            }else{
               $response = response('',406);
            }

        }else{
            $response = response('comment already has',406);
        }
        return $response;
    }
}
