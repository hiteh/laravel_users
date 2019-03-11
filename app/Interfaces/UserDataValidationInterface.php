<?php

namespace App\Interfaces;


interface UserDataValidationInterface 
{
	public function validateUserCreationData( array $data, string $id );

	public function validateUserRegistrationData( array $data );

}