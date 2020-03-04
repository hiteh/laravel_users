<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    /**
     * Use faker.
     *
     */
    use WithFaker;

    /**
     * Reset database after each test.
     *
     */
    use RefreshDatabase;


    public function testRegisterControllerResponse()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();

        $response = $this->post( '/register', [
            'name'                  => $name,
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $password,
        ] );

        $this->assertDatabaseHas( 'users', [
            'name'            => $name,
            'email'           => $email, 
        ] );

        $response->assertStatus( 302 );
        $response->assertRedirect( 'home' );
    }


    public function testRegisterControllerResponseWhenUsersRepositoryIsEmpty()
    {
        $response = $this->get( '/register' );

        $response->assertStatus( 302 );
        $response->assertRedirect( '/' );
    }

}