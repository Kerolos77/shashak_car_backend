<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role management
        Gate::define('role_access', function ($user) {
            return $user->can('role_access');
        });

        Gate::define('role_create', function ($user) {
            return $user->can('role_create');
        });

        Gate::define('role_edit', function ($user) {
            return $user->can('role_edit');
        });

        Gate::define('role_show', function ($user) {
            return $user->can('role_show');
        });

        Gate::define('role_delete', function ($user) {
            return $user->can('role_delete');
        });
    }
}
