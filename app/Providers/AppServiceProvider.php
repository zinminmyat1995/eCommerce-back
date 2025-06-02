<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Classes\Repositories\UserRegistrationRepository;
use App\Classes\Repositories\LoginRepository;

use App\Interfaces\{UserRegistrationInterface};
use App\Interfaces\{LoginInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRegistrationInterface::class,UserRegistrationRepository::class); 
        $this->app->bind(LoginInterface::class,LoginRepository::class); 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
