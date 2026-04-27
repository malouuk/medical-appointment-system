<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Only admin can access admin routes
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });

        // Doctors and admins can manage appointments
        Gate::define('manage-appointments', function ($user) {
            return in_array($user->role, ['admin', 'medecin']);
        });
    }
}
