<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 'App\Interfaces\UsersRepositoryInterface', 'App\Repositories\UsersRepository' );
        $this->app->bind( 'App\Interfaces\RolesRepositoryInterface', 'App\Repositories\RolesRepository' );
        $this->app->bind( 'App\Interfaces\UserDataValidationInterface', 'App\Services\UserDataValidationService' );
    }
}
