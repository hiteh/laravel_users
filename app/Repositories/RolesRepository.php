<?php

namespace App\Repositories;
use App\Interfaces\RolesRepositoryInterface;
use App\Role;

class RolesRepository implements RolesRepositoryInterface
{
	public function getAvailableRolesList()
	{
		return Role::where( 'name','<>','root' )->pluck( 'name' );
	}

	public function getRoleByName( string $name )
	{
		return Role::where( 'name', $name )->first();
	}
}