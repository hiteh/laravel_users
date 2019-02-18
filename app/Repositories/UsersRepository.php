<?php

namespace App\Repositories;
use App\Interfaces\UsersRepositoryInterface;
use App\Interfaces\RolesRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\User;

class UsersRepository implements UsersRepositoryInterface
{
    /**
     * Roles repository instance.
     *
     * @var App\Repositories\RolesRepository
     */
    protected $roles;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct( RolesRepositoryInterface $roles )
    {
        $this->roles = $roles;
    }

    public function hasAnyUser()
    {
        return User::all()->first()? true : false;
    }

	public function getAllUsers( array $order = ['created_at', 'asc'], int $items_per_page = 10 )
	{
		return User::orderBy( ...$order )->paginate( $items_per_page );
	}

	public function getUserById( string $id )
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

        $user->roles()->attach( $this->roles->getRoleByName( $data['role'] ) );

        return $user;
	}

	public function deleteUser( string $id )
	{

		$user = $this->getUserById( $id );

        $user->roles()->detach();
        $user->delete();

        return $user;
	}

	public function updateUser( string $id, array $data )
	{
		$user = $this->getUserById( $id );

        if ( $user->isRoot() && ! empty( $data['role']) )
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
            else if ( 'avatar' === $field )
            {
                $value->store('public');

                Storage::delete( 'public/'.$user->avatar );

                $user->update([
                    'avatar' => $value->hashName(),
                ]);
            }
            else if ( 'role' === $field )
            {
	            $user->roles()->detach();

	            $user->roles()->attach( $this->roles->getRoleByName( $value ) );
	        }
            else
            {
                $user->update([
                    $field => $value,
                ]);
            }
        }

        return $user;
	}
}