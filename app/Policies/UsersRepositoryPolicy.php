<?php

namespace App\Policies;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
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
    public function view( User $user, User $model, Request $request )
    {
        if( isset( $request->id) ) // Every user can see his/her own profile.
        {
            $target = $model->all()->find( $request->id );
            return isset( $target ) && $user->id === $target->id;
        }

        return $user->hasRole('root') || $user->hasRole('admin'); // Only root or admin can see all users.
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
    public function update( User $user, User $model, UpdateUser $request )
    {
        $targetId = $request->id;
        $targetRole = $request->get('role');

        if( isset( $targetId ) ) // User's id must exist.
        {
            $target = $model->all()->find( $targetId );

            if( isset( $target ) && $user->id === $target->id ) // User can edit his/her own data.
            {
                if( isset( $targetRole ) && ! $target->hasRole( $targetRole ) ) { return false; } // User can't change his/her own role.
                return true;
            }

            if( isset( $target ) && isset( $targetRole ) && $user->hasRole( 'root' ) || $user->hasRole( 'admin' ) )
            {
                if( $user->hasRole( 'admin' ) && $target->hasRole( 'root' ) ) { return false; } // Admin can't change root user data.
                if( $user->hasRole( 'admin' ) && $target->hasRole( 'admin' ) ) { return false; } // Admin can't change another admin's data.
                if( $target->hasRole( 'root') && 'root' !== $targetRole ) { return false; } // Nobody can change root's role
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
    public function delete( User $user, User $model, Request $request )
    {
        $targetId = $request->id;
        $target = $model->all()->find( $targetId );

        if( $user->id === $targetId ) { return false; } // User cant remove himself/herself.
        if( $target->hasRole( 'root' ) ) { return false; } // Root user can't be removed.
        if( $user->hasRole( 'admin' ) && $target->hasRole( 'admin' ) ) { return false; } // Admin can't remove another admin.
        if( $user->hasRole( 'root' ) || $user->hasRole( 'admin' ) ) 
        {
            return true;
        }
        return false;
    }
}
