<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublishPostRequest;
use App\Jobs\HandlerPost;
use App\Models\Post;
use App\Models\SavedPost;
use App\Wrappers\NonHandledPostWrapper;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function ban(Request $request, $tredId)
    {
        try{
            $postInstance = Post::findOrFail($tredId);

        }catch (ModelNotFoundException $e){
            return response('',404);
        }

        $isBanned = $postInstance->alreadyBanned(Auth::id());

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

        if(!$postInstance->alreadyVoted(Auth::id())){
            $postInstance->increment('to_top');
            $postInstance->votedUsers()->attach(Auth::id(),['vote_type'=>'TOP']);

            $response = response('',204);
        }else{
            $response = response('Вы уже ставили оценку',406);
        }

        return $response;
    }

    public function postDown($postId)
    {
        try{
            $postInstance = Post::findOrFail($postId);
        }catch (ModelNotFoundException $e){
            return response('Этот пост не существует',404);
        }

        if(!$postInstance->alreadyVoted(Auth::id())){
            $postInstance->increment('to_down');
            $postInstance->votedUsers()->attach(Auth::id(),['vote_type'=>'DOWN']);

            $response = response('',204);
        }else{
            $response = response('Вы уже ставили оценку',406);
        }

        return $response;
    }

    public function save(Request $request)
    {
        $validation = Validator::make($request->only(['body','title','id']),[
           'body'=>['required','max:60','string'],
           'title'=>['required','string'],
           'id'=>['integer']
        ]);

        if(!$validation->fails()){
            $response = $request->get('id') === null ? $this->saveNew($request->only(['body','title'])) :
                                                            $this->saveExisting($request->only(['body','title','id']));
        }else{
            $response = response('Неверный формат ',406);
        }

        return $response;
    }

    public function publish(PublishPostRequest $request,NonHandledPostWrapper $wrapper)
    {
            $validatedInput = $request->validated();
            $wrapper->setPostTitle($validatedInput['title']);
            $wrapper->setPostBody($validatedInput['body']);
            $wrapper->setCommunityId($validatedInput['communityForSendId']);
            $wrapper->setUserId(Auth::id());

            HandlerPost::dispatch($wrapper);

            return response('',204);
    }

    private function saveNew(array $inputs)
    {
        $savedPostsInstance = Auth::user()->savedPosts()->save(new SavedPost([
                            'body'=>$inputs['body'],
                            'title'=>$inputs['title']
                            ]));
        return response($savedPostsInstance,200);
    }

    private function saveExisting(array $inputs)
    {
        $this->authorize('update-saved-post',$inputs['id']);

        $savedPosts = SavedPost::find($inputs['id']);
        $savedPosts->title = $inputs['title'];
        $savedPosts->body = $inputs['body'];
        Auth::user()->savedPosts()->save($savedPosts);

        return response($savedPosts,200);
    }

}
