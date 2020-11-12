<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const AUTH_MIDDLEWARE = 'auth:sanctum';
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//*******************Auth**********************

Route::get('/auth',[\App\Http\Controllers\AuthController::class,'authenticate']);

//*****************LogOut*********************

Route::delete('/profile/logOut',[\App\Http\Controllers\LoginController::class,'logOut']);

//***************LogIn|Register*************************

Route::post('/login',[\App\Http\Controllers\LoginController::class,'login']);


Route::post('/register',[\App\Http\Controllers\RegisterController::class,'register']);

//*******************TRED**********************

Route::get('/tred/{tredId}/general',[\App\Http\Controllers\TredController::class,'getGeneral']);

Route::get('/tred/{tredId}/comments',[\App\Http\Controllers\CommentController::class,'getTredComments']);

Route::get('/tred/{tredId}/others',[\App\Http\Controllers\TredController::class,'getOthersPosts']);

Route::middleware(AUTH_MIDDLEWARE)->patch('/comment/{commentId}/up',[\App\Http\Controllers\CommentController::class,'commentUp']);

Route::middleware(AUTH_MIDDLEWARE)->patch('/comment/{commentId}/down',[\App\Http\Controllers\CommentController::class,'commentDown']);

Route::middleware(AUTH_MIDDLEWARE)->post('/tred/{tredId}/ban',[\App\Http\Controllers\PostController::class,'ban']);

Route::middleware(AUTH_MIDDLEWARE)->patch('/post/{postId}/up',[\App\Http\Controllers\PostController::class,'postUp']);


Route::middleware(AUTH_MIDDLEWARE)->patch('/post/{postId}/down',[\App\Http\Controllers\PostController::class,'postDown']);

Route::middleware(AUTH_MIDDLEWARE)->post('/tred/{tredId}/comment',[\App\Http\Controllers\CommentController::class,'addComment']);

Route::middleware(AUTH_MIDDLEWARE)->post('/community/{communityId}/join',[\App\Http\Controllers\CommunityController::class,'join']);

//********************Lenta*********************

Route::get('/lenta',[\App\Http\Controllers\LentaController::class,'getLenta']);

Route::get('/community/popular',[\App\Http\Controllers\CommunityController::class,'getPopular']);

//*************************CreateCommunity*********************

Route::middleware(AUTH_MIDDLEWARE)->post('/community',[\App\Http\Controllers\CommunityController::class,'create']);

//********************************CreatePost***********************

Route::get('/community/search',function (Request $request) {
    return [
        ['name'=>'test','id'=>1],
        ['name'=>'test_2','id'=>2]
    ];
});

Route::get('/post/{id}/saved',function($id) {
    return [
        'title'=>'test',
        'body'=>'test_body'
    ];
});

Route::post('/post/publish',function (Request  $request) {
    return ['ответ'=>$request->all()];
});

Route::patch('/post/save',function (Request $request) {
    return ['status'=>$request->all()];
});

Route::delete('/post/saved/{savedPostId}',function (Request $request) {
    return ['ok'];
});

//*************************** Saved *********************************

Route::get('/saved/preview',function (Request  $request) {
    return [
        ['title'=>'test',
            'body'=>str_repeat('test',200),
            'last_update_time'=>123123123,
            'id'=>1],
        ['title'=>'test2',
            'body'=>'test_body2',
            'last_update_time'=>123123123,
            'id'=>2],
        ['title'=>'test3',
            'body'=>'test_body3',
            'last_update_time'=>123123123,
            'id'=>3],
        ['title'=>'test4',
            'body'=>'test_body4',
            'last_update_time'=>123123123,
            'id'=>4]
    ];
});
Route::delete('/saved/{id}',function ($id){
    return "Удалю по этому Айди : ".$id;
});

//******************InQueue***********************8
Route::get('/in_queue',function(Request $request) {
    return [
        ['title'=>'test','date'=>6666,'status'=>'waiting-server'],
        ['title'=>'test2','date'=>6666,'status'=>'published'],
        ['title'=>'test3','date'=>6666,'status'=>'waiting-admin'],
        ['title'=>'test4','date'=>6666,'status'=>'server-error'],
        ['title'=>'test5','date'=>6666,'status'=>'waitiver'],
        ['title'=>'test3','date'=>6666,'status'=>'waiting-admin'],
        ['title'=>'test4','date'=>6666,'status'=>'server-error'],
        ['title'=>'test5','date'=>6666,'status'=>'waitiver'],
    ];
});

//****************************Settings********************
Route::middleware(AUTH_MIDDLEWARE)->patch('/setting/nickname',
                                            [\App\Http\Controllers\ProfileController::class,'changeNickName']);

Route::middleware(AUTH_MIDDLEWARE)->patch('/setting/password',[\App\Http\Controllers\ProfileController::class,'changePassword']);

Route::delete('setting/profile',[\App\Http\Controllers\ProfileController::class,'deleteProfile']);

//***************************Statistic*********************
Route::get('/statistic',function (Request $request) {
    $onePostArr = [];
    $oneYearInSeconds = 31536000;
    for($i = 0;$i < 100;$i++){
        $onePostArr[] = [
            'date'=>mt_rand(time()-$oneYearInSeconds,time()),
            'title'=>'test'.mt_rand(100000,1000000000),
            'views'=>mt_rand(100,1000),
            'toTop'=>mt_rand(100,500),
            'toDown'=>mt_rand(100,500)
        ];
    }
    return $onePostArr;
});
//************************** NEW|HOT|TOP *********************
Route::get('/{type}/new',function (){
    $posts = [
        ['user'=>'test','date'=>123,'comments_count'=>45,'view_count'=>34,'title'=>'test_title','post_id'=>1],
        ['user'=>'test2','date'=>1235,'comments_count'=>455,'view_count'=>343,'title'=>'test_title2','post_id'=>2],
        ['user'=>'test3','date'=>3500,'comments_count'=>5,'view_count'=>56,'title'=>'test_title3','post_id'=>3],
    ];
   return $posts;
});

Route::get('/{type}/top',function (){
    $posts = [
        ['user'=>'test','date'=>123,'comments_count'=>45,'view_count'=>34,'title'=>'test_title','post_id'=>1],
        ['user'=>'test2','date'=>1235,'comments_count'=>455,'view_count'=>343,'title'=>'test_title2','post_id'=>2],
        ['user'=>'test3','date'=>3500,'comments_count'=>5,'view_count'=>56,'title'=>'test_title3','post_id'=>3],
    ];
    return $posts;
});

Route::get('/{type}/hot',function (){
    $posts = [
        ['user'=>'test','date'=>123,'comments_count'=>45,'view_count'=>34,'title'=>'test_title','post_id'=>1],
        ['user'=>'test2','date'=>1235,'comments_count'=>455,'view_count'=>343,'title'=>'test_title2','post_id'=>2],
        ['user'=>'test3','date'=>3500,'comments_count'=>5,'view_count'=>56,'title'=>'test_title3','post_id'=>3],
        ['user'=>'test','date'=>123,'comments_count'=>45,'view_count'=>34,'title'=>'test_title','post_id'=>1],
        ['user'=>'test2','date'=>1235,'comments_count'=>455,'view_count'=>343,'title'=>'test_title2','post_id'=>2],
        ['user'=>'test3','date'=>3500,'comments_count'=>5,'view_count'=>56,'title'=>'test_title3','post_id'=>3],
        ['user'=>'test','date'=>123,'comments_count'=>45,'view_count'=>34,'title'=>'test_title','post_id'=>1],
        ['user'=>'test2','date'=>1235,'comments_count'=>455,'view_count'=>343,'title'=>'test_title2','post_id'=>2],
        ['user'=>'test3','date'=>3500,'comments_count'=>5,'view_count'=>56,'title'=>'test_title3','post_id'=>3],

    ];
    return $posts;
});
