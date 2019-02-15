<?php

namespace App\Interfaces;


interface UsersRepositoryInterface 
{
	public function getAllUsers( array $order, int $items_per_page );

	public function getUserById( $id );

	public function addUser( array $data );

	public function deleteUser( $id );

	public function updateUser( $id, array $data );
}