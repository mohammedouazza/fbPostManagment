<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Publishable;

class Post extends Model
{
    use Publishable;

    protected $guarded = [];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
