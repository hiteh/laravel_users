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
            $root =  $user->roles()->where( 'name', 'root' )->exists();
            $admin = $user->roles()->where( 'name', 'admin' )->exists();

            return $root || $admin;
        } );

        Gate::define( 'delete-user', function( $user, $id ) {
            $self = $user->id == $id;
            $root =  $user->roles()->where( 'name', 'root' )->exists();
            $admin = $user->roles()->where( 'name', 'admin' )->exists();
            $target_is_root = User::all()->find( $id )->roles()->where( 'name', 'root' )->exists();
            $target_is_admin = User::all()->find( $id )->roles()->where( 'name', 'admin' )->exists();

            return ! $self && ( $root || $admin ) && ! $target_is_root && ! ( $admin && $target_is_admin );
        } );

        Gate::define( 'update-user', function( $user, $id ) {
            $self = $user->id == $id;
            $root =  $user->roles()->where( 'name', 'root' )->exists();
            $admin = $user->roles()->where( 'name', 'admin' )->exists();
            $target_is_root = User::all()->find( $id )->roles()->where( 'name', 'root' )->exists();
            $target_is_admin = User::all()->find( $id )->roles()->where( 'name', 'admin' )->exists();


            return $root || ( $admin && ! $target_is_root && ! ( $admin && $target_is_admin && ! $self ) );
        } );
    }
}
