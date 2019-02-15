<?php

namespace App\Repositories;
use App\Interfaces\UsersRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class UsersRepository implements UsersRepositoryInterface
{
	public function getAllUsers( array $order = ['created_at', 'asc'], int $items_per_page = 10 )
	{
		return User::orderBy( ...$order )->paginate( $items_per_page );
	}

	public function getUserById( $id )
	{
		return User::all()->find( $id );
	}

	public function addUser( array $data )
	{
		$user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make( $data['password'] ),
        ]);

        $user->roles()->attach( Role::where( 'name', $data['role'] )->first() );

        return $user;
	}

	public function deleteUser( $id )
	{
		$user = $this->getUserById( $id );

        $user->roles()->detach();
        $user->delete();

        return $user;
	}

	public function updateUser( $id, array $data )
	{
		$user = $this->getUserById( $id );

        if ( $user->roles()->where( 'name', 'root' )->exists() && ! empty( $data['role']) )
        {

           unset( $data['role'] );

        }

        foreach ( $data as $field => $value )
        {
            if ( 'password' === $field )
            {
                $user->update([
                    $field => Hash::make( $value ),
                ]);
            }
            else
            {
                $user->update([
                    $field => $value,
                ]);
            }
        }

        if ( ! empty( $data['role']) )
        {
            $user->roles()->detach();

            $user->roles()->attach( Role::where( 'name', $data['role'] )->first() );
        }

        return $user;
	}
}