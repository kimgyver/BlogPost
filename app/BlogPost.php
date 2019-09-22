<?php

namespace App;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    //protected $table = 'blogposts';

    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        return $this->hasMany('App\Comment')->latest();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag');
    }

    public function scopeLatest(Builder $builder)
    {
        return $builder->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $builder)
    {
        // comments_count
        return $builder->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $builder)
    {
        return $builder->latest()->withCount('comments')->with('user')->with('tags');
    }

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);  // due to "use SoftDeletes", it should be here. 
        parent::boot();

        // static::addGlobalScope(new LatestScope);

        static::deleting(function (BlogPost $blogPost) {
            $blogPost->comments()->delete();
        });

        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }
}
