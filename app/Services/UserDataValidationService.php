<?php

namespace App\Services;
use App\Interfaces\UserDataValidationInterface;
use App\Interfaces\RolesRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserDataValidationService implements UserDataValidationInterface
{
	/**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct( RolesRepositoryInterface $roles )
    {
        $this->roles = $roles;
    }

	public function validateUserData( array $data, string $id = null )
	{
		$user =  Validator::make( $data, [
            'name'     => ['sometimes','required', 'string', 'max:255'],
            'email'    => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users,email,'.$id ],
            'password' => ['sometimes','required', 'string', 'min:6', 'confirmed'],
            'role'     => ['sometimes','required', 'string', Rule::in( $this->roles->getAvailableRolesList() ) ],
            'avatar'   => ['sometimes', 'required', 'image'],
        ] );

        return $user->validate();
	}
}