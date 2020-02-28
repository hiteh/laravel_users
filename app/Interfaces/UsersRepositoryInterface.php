<?php

namespace App\Interfaces;


interface UsersRepositoryInterface 
{
	public function hasAny();

	public function response();

	public function roles();

	public function paginated( int $items_per_page, array $query, string $url, string $key );

	public function create( array $data );

	public function read( string $id = null );

	public function update( array $data, string $id );

	public function delete( string $id  );
}