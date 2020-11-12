<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = ['name','rules','description','user_id'];


    public function owner()
    {
        return $this->belongsTo(User::class)->select(['name','id']);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class,'subscriptions')->withTimestamps();
    }

    public function scopeIsJoin($query,int $user_id):bool
    {
        return $this->belongsToMany(User::class,'subscriptions')
                ->where('user_id',$user_id)
                ->get()
                ->isNotEmpty();
    }

    public function scopePopular($query)
    {
       return $query->orderBy('touched','desc')
                    ->select(['name','id']);
    }

}
