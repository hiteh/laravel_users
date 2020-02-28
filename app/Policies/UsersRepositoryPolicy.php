<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersRepositoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view( User $user, User $model )
    {
        if( isset( request()->id ) && ! ( $user->hasRole( 'root' ) || $user->hasRole( 'admin' ) ) )
        {
            $target = $model->all()->find( request()->id );
            return isset( $target ) && $user->id === $target->id;
        }

        return $user->hasRole('root') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create( User $user )
    {
        return $user->hasRole('root') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update( User $user, User $model )
    {
        $targetId = request()->id;
        $targetRole = request()->get('role');

        if( isset( $targetId ) ) // User's id can't be undefined
        {
            $target = $model->all()->find( $targetId );

            if( isset( $target ) && $user->id === $target->id ) // User can edit his/her own data
            {
                if( isset( $targetRole ) && ! $target->hasRole( $targetRole ) ) { return false; } // User can't change his/her own role
                return true;
            }

            if( $user->hasRole( 'root' ) || $user->hasRole( 'admin' ) )
            {
                if( $user->hasRole( 'admin' ) && $target->hasRole( 'root' ) ) { return false; } // Admin can't change root's role
                if( $user->hasRole( 'admin' ) && $target->hasRole( 'admin' ) ) { return false; } // Admin can't change another admin's role
                if( $target->hasRole( 'root') && isset( $targetRole ) && 'root' !== $targetRole ) { return false; } // Nobody can change root's role
                return true;
            }    
            return false;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete( User $user, User $model )
    {
        $target = $model->all()->find( request()->id );

        return ! ( $user->id === $target->id )  && ! $target->hasRole( 'root' ) && ! ( $user->hasRole( 'admin' ) && $target->hasRole( 'admin' ) );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
