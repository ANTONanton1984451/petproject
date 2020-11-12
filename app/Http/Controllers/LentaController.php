<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LentaController extends Controller
{
    private const PAGINATE_LIMIT = 10;
    //todo::Продумать,ситуацию,где у пользователя нет ни одной подписки
    public function getLenta(Request $request)
    {
        $posts = Auth::check() ? $this->getPostsForAuth() : $this->getPostsForGuest();

        return response($posts->simplePaginate(self::PAGINATE_LIMIT));
    }

    private function getPostsForAuth()
    {
        $authUser = Auth::user();
        $banPostsIdList = $authUser->bannedPosts()
                                    ->get()
                                    ->pluck('pivot.post_id')
                                    ->toArray();

        $subscriptionsIdList = $authUser->subscriptions()
                                        ->get()
                                        ->pluck('pivot.community_id')
                                        ->toArray();

        $posts = Post::forAuth($subscriptionsIdList,$banPostsIdList)
                ->with(['user:id,name','community:id,name'])
                ->limit(self::PAGINATE_LIMIT);

        return $posts;
    }

    private function getPostsForGuest()
    {
        $posts = Post::forGuest()
                ->limit(self::PAGINATE_LIMIT);

        return $posts;
    }
}
