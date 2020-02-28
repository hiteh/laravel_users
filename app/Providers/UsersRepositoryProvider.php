<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UsersRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind( 'App\Interfaces\UsersRepositoryInterface', 'App\Repositories\UsersRepository' );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
