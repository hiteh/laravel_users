<?php

namespace App\Interfaces;


interface UsersRepositoryInterface 
{
	public function getAllUsers( array $order, int $items_per_page );

	public function getUserById( string $id );

	public function addUser( array $data );

	public function deleteUser( string $id );

	public function updateUser( string $id, array $data );
}