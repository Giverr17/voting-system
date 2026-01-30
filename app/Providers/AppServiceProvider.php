<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
    }
}
