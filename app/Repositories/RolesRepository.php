<?php

namespace App\Repositories;

use App\Role;
use App\Abstracts\CrudableRepository;
use App\Interfaces\RolesRepositoryInterface;


class RolesRepository extends CrudableRepository implements RolesRepositoryInterface
{
	public function __construct( Role $role )
	{
		$this->model = $role;
	}
}