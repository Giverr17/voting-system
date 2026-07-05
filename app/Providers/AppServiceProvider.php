<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Gate::define('access-election',function(?User $user){   
        //     if($user && $user->role==='admin'){
        //         return true;
        //     }

        //     return config('election.status') ==='open';
        // });

        // Model::preventLazyLoading();
        if (env('APP_ENV') !== 'local') {
            Schema::defaultStringLength(191);
        }
        // Force HTTPS everywhere except production. Set FORCE_HTTPS=false in your
        // local .env (git-ignored) to disable it for local http:// testing.
        // Defaults to true, so committed behaviour and production are unchanged.
        if (config('app.env') !== "production" && env('FORCE_HTTPS', true)) {
            URL::forceScheme('https');
        }
    }
}
