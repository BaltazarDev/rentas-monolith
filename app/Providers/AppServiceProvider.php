<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $storagePaths = [
            storage_path('framework/views'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            storage_path('app/public/houses'),
        ];

        foreach ($storagePaths as $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0775, true);
            }
        }

        // Super Admin bypass
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
        });

        // Register all application permissions with Gate
        foreach (User::getAllPermissionKeys() as $permissionKey) {
            Gate::define($permissionKey, function ($user) use ($permissionKey) {
                return method_exists($user, 'hasPermission') ? $user->hasPermission($permissionKey) : false;
            });
        }
    }
}
