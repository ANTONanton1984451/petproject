<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $gateFunction = function ($user,int $savedPostId){
            return $user->savedPosts()
                ->where('saved_posts.id','=',$savedPostId)
                ->get()
                ->isNotEmpty();
        };

        Gate::define('update-saved-post',$gateFunction);
        Gate::define('delete-save-post',$gateFunction);
        Gate::define('get-saved',$gateFunction);
    }
}
