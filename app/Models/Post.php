<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $appends = ['comments_count'];

    public function user() : BelongsTo
    {
       return $this->belongsTo(User::class)->select(['id','name']);
    }

    public function community() : BelongsTo
    {
       return $this->belongsTo(Community::class)->select(['name','id']);
    }

    public function banners() : BelongsToMany
    {
        return $this->belongsToMany(User::class,'ban_lists')->withTimestamps();
    }

    public function comments() : HasMany
    {
      return  $this->hasMany(Comment::class);
    }

    public function setCommentsCountAttribute()
    {
        $this->appends['comments_count'] = $this->hasMany(Comment::class)->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->hasMany(Comment::class)->count();
    }

    public function scopeOthersWithBans($query,int $authId)
    {
        //todo :: Работает но вызывает подозрения
      return  $query->selectRaw('posts.id,body,community_id,title,posts.created_at,to_top,to_down,views,posts.user_id')
                    ->leftJoin('ban_lists','ban_lists.post_id','=','posts.id')
                    ->where('community_id','=',$this->community_id)
                    ->where('posts.id','!=',$this->id)
                    ->where(function ($query) use ($authId){
                        $query->where('ban_lists.user_id','=',null)
                            ->orWhere('ban_lists.user_id','!=',$authId);
                    });
    }

    public function scopeForAuth($query,array $communityIdList,array $bannedPostsList)
    {
        return $query->whereIn('community_id',$communityIdList)
                     ->whereNotIn('id',$bannedPostsList);
    }

    public function scopeForGuest($query)
    {
        return $query->with(['user:name,id','community:name,id'])
                     ->orderBy('created_at','desc');
    }
}
