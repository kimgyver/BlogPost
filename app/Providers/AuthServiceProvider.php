<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\BlogPost' => 'App\Policies\BlogPostPolicy',
        'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function($user) {
            return $user->is_admin;
        });

        // Gate::define('update-post', function($user, $post) {
        //     return $user->id == $post->user_id;
        // });

        // Gate::define('delete-post', function($user, $post) {
        //     return $user->id == $post->user_id;
        // });

        Gate::Before(function($user, $ability) {
            if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
                return true;
            }
        });
    }
}
