<?php

namespace App\Providers;

use App\Models\User;
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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerCustomGates();
    }

    protected function registerCustomGates()
    {
        // Define gates for each permission
        foreach (User::$permissions as $role => $permissions) {
            foreach ($permissions as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    if (! $user) {
                        return false;
                    }
                    // Super admin has all permissions
                    if ($user->isSuperAdmin()) {
                        return true;
                    }
                    // Check if the user has this permission via their role
                    return $user->hasPermissionTo($permission);
                });
            }
        }

        // Allow super admin to do anything
        Gate::before(function ($user, $ability) {
            if (! $user || ! $user->isSuperAdmin()) {
                return null;
            }
            return true;
        });
    }
}