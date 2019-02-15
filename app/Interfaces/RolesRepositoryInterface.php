<?php

namespace App\Interfaces;


interface RolesRepositoryInterface 
{
	public function getAvailableRolesList();

	public function getRoleByName( string $name );
}