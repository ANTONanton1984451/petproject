<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
      'text',
      'user_id',
      'post_id'
    ];

    public function user()
    {
      return $this->belongsTo(User::class)->select(['name','id']);
    }
    public function post()
    {
      return $this->belongsTo(Post::class);
    }

    public function scopeAlreadyHas($query, ?int $user_id, int $post_id) :bool
    {
        return $query->where([
            ['user_id',$user_id],
            ['post_id',$post_id]
        ])->get()->isNotEmpty();
    }

    public function scopeNew($query,int $post_id)
    {
        return $query->where([
            ['post_id',$post_id]
        ])
        ->with('user:name,id')
        ->orderBy('created_at','desc');
    }

    public function scopeOld($query,int $post_id)
    {
        return $query->where([
            ['post_id',$post_id]
        ])
        ->with('user:name,id')
        ->orderBy('created_at');

    }

    public function scopeTop($query,int $post_id)
    {
        //todo:: ммм,Хардкод
        return $query->selectRaw('id,user_id,created_at,to_down,to_top,text,to_top + to_down as top_result')
            ->where([
                ['post_id',$post_id]
            ])
            ->with('user:name,id')
            ->orderBy('top_result','desc');

    }
}
