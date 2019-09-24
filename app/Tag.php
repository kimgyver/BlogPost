<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function blogPosts() 
    {
        return $this->morphedByMany('App\BlogPost', 'taggable')->as('tagged');
    }

    public function comments() 
    {
        return $this->morphedByMany('App\Comment', 'taggable')->as('tagged');
    }
}
