<?php

namespace App\Interfaces;


interface RolesRepositoryInterface 
{
	public function hasAny();

	public function response();

	public function create( array $data );

	public function read( string $id = null );

	public function update( array $data, string $id );

	public function delete( string $id  );
}