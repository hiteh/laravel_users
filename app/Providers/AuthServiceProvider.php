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

        Gate::define( 'create-user', function ( $user ) {
            return $user->hasRole('root') || $user->hasRole('admin');
        } );

        Gate::define( 'delete-user', function ( $user, $id ) {

            if ( User::all()->find( $id ) ) 
            {
                $self = $user->id == $id;
                $target_is_root = User::all()->find( $id )->roles()->where( 'name', 'root' )->exists();
                $target_is_admin = User::all()->find( $id )->roles()->where( 'name', 'admin' )->exists();

                return ! $self && ( $user->hasRole('root') || $user->hasRole('admin') ) && ! $target_is_root && ! ( $user->hasRole('admin') && $target_is_admin );
            }

            return false;

        } );

        Gate::define( 'update-user', function ( $user, $id ) {

            if ( User::all()->find( $id ) )
            {
                $self = $user->id == $id;
                $target_is_root = User::all()->find( $id )->roles()->where( 'name', 'root' )->exists();
                $target_is_admin = User::all()->find( $id )->roles()->where( 'name', 'admin' )->exists();


                return $user->hasRole('root') || ( $user->hasRole('admin') && ! $target_is_root && ! ( $user->hasRole('admin') && $target_is_admin && ! $self ) );
            }

            return false;

        } );
    }
}
