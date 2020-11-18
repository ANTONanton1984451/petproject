<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    use HasFactory;

    protected $fillable = ['body','title'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
