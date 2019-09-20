<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function blogPosts() 
    {
        return $this->belongsToMany('App\BlogPost')->as('tagged');  // 'pivot' table name => 'tagged'
    }
}
