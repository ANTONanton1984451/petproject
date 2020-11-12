<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TredController extends Controller
{
    public function getGeneral(Request $request,$tredId)
    {
        try{
            $mainPost = Post::findOrFail(+$tredId);
            $mainPost->user;
            $community = $mainPost->community;
            //todo :: проверить работу при большом количестве подписок
            $isJoin = Auth::check() ? false : Auth::user()
                                              ->subscriptions()
                                              ->where('community_id',$community->id)
                                              ->get()
                                              ->isNotEmpty();
        }catch (ModelNotFoundException $e){
            return response('Тред не найден',404);
        }

        $hasComment = Comment::alreadyHas(Auth::id(),$mainPost->id);

        $otherPosts = $mainPost->othersWithBans(Auth::id())->limit(5)->with('user:name,id')->get()->toArray();

        return response([
             'main_post'=>$mainPost,
             'is_joined'=>$isJoin,
             'next_page_posts'=>"http://offtop/api/tred/{$tredId}/others?page=2",
             'has_comment'=>$hasComment,
             'other_posts'=>$otherPosts
        ]);
    }

    public function getOthersPosts(Request $request,$tredId)
    {
        try{
            $mainPost = Post::findOrFail(+$tredId);
        }catch (ModelNotFoundException $e){
            return response('тред не найден',404);
        }

        return response($mainPost->othersWithBans(Auth::id())->limit(5)->with('user:name,id')->simplePaginate(5));
    }
}
