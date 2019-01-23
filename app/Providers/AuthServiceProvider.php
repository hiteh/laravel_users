<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define( 'create-user', function( $user ) {
            return $user->roles()->where( 'name', 'root' )->orWhere( 'name', 'admin' )->get()->first();
        } );

        Gate::define( 'delete-user', function( $user, $id ) {
            return $user->id != $id && 
                $user->roles()->where( 'name', 'root' )->orWhere( 'name', 'admin' )->get()->first() &&
                empty( User::all()->find( $id )->roles()->where( 'name', 'root' )->get()->first() );
        } );

        Gate::define( 'update-user', function( $user, $id ) {
            return $user->roles()->where( 'name', 'root' )->get()->first() ||
                ( 
                    $user->roles()->where( 'name', 'admin' )->get()->first() &&
                    empty( User::all()->find( $id )->roles()->where( 'name', 'root' )->get()->first() ) 
                );
        } );
    }
}
