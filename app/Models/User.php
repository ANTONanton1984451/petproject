<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //todo :: Убрать fillable и сделать сеттер к паролю
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
       return $this->hasMany(Comment::class);
    }

    public function posts()
    {
      return  $this->hasMany(Post::class);
    }

    public function communities()
    {
        return $this->hasMany(Community::class);
    }
    public function bannedPosts()
    {
        return $this->belongsToMany(Post::class,'ban_lists')->withTimestamps();
    }

    public function votes()
    {
        return $this->belongsToMany(Post::class,'votes')->withTimestamps();
    }

    public function voted_comments()
    {
        return $this->belongsToMany(Comment::class,'comment_votes');
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Community::class,'subscriptions');
    }

    public function savedPosts()
    {
        return $this->hasMany(SavedPost::class);
    }

    public function scopeSecure($query)
    {
        return $query->select('name','id');
    }
}
