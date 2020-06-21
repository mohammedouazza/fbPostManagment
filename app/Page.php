<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['active'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
