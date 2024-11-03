<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', fn(User $user) => $user->isAdmin());
        Gate::define('business', fn(User $user) => $user->isBusiness());
        Gate::define('manage-employees', fn(User $user) => $user->isBusiness());
        Gate::define('create-courses', fn(User $user) => $user->isAdmin());
    }
} 