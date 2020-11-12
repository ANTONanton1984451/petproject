<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User([
           'name'=>'test',
           'email'=>'test',
           'password'=>Hash::make('test')
        ]);
        $user->save();

        $community = new Community([
            'user_id'=>$user->id,
            'name'=>'test',
            'description'=>'test',
            'rules'=>'test',
        ]);
        $community->save();
        $post_1 = new Post([
            'community_id'=>$community->id,
            'body'=>'test',
            'title'=>'test',
            'to_top'=>123,
            'to_down'=>123,
            'views'=>123
        ]);

        $post_2 = new Post([
            'community_id'=>$community->id,
            'body'=>'test2',
            'title'=>'test2',
            'to_top'=>123,
            'to_down'=>123,
            'views'=>123
        ]);

        $user->posts()->saveMany([
            $post_1,
            $post_2,
        ]);
        $comment_1 = new Comment([
            'user_id'=>$user->id,
            'text'=>'test',
            'to_top'=>123,
            'to_down'=>123,
        ]);

        $comment_2 = new Comment([
            'user_id'=>$user->id,
            'text'=>'test',
            'to_top'=>23,
            'to_down'=>3,
        ]);
        $post_1->comments()->saveMany([
            $comment_1,
            $comment_2
        ]);

        $post_2->comments()->saveMany([
            $comment_1,
        ]);
    }
}
