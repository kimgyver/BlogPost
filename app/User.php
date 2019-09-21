<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPosts()
    {
        return $this->hasMany('App\BlogPost');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function scopeMostBlogPosts(Builder $builder)
    {
        // blog_posts_count
        return $builder->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function scopeMostBlogPostsLastMonth(Builder $builder)
    {
        return $builder->withCount(['blogPosts' => function(Builder $builder) {
            $builder->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])
        ->having('blog_posts_count', '>=', 2)
        ->orderBy('blog_posts_count', 'desc');
    }
}
