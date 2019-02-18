<?php

namespace App\Interfaces;


interface UserDataValidationInterface 
{
	public function validateUserData( array $data, string $id );

}