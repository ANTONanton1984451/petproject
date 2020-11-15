<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LentaController extends Controller
{
    private const PAGINATE_LIMIT = 10;
    //todo::Продумать,ситуацию,где у пользователя нет ни одной подписки
    public function getLenta()
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


        //todo :: Здесь возникает небольшой костыль,в виде массива отношения votedUsers
        // с одним только элементом это происходит потому что связь многие ко многим и Laravel создаёт масссив массивов,
        //однако в подмассивах только один элемент
        $posts = Post::forAuth($subscriptionsIdList,$banPostsIdList,Auth::id())
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
