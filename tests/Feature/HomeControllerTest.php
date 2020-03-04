<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{   
    /**
     * Reset database after each test.
     *
     */
    use RefreshDatabase;

    /**
     * Test controller response status for logged in user.
     *
     * @return void
     */
    public function testLoggedInUserResponseStatus()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs( $user, 'web' )->get( '/home' );

        $response->assertStatus( 200 );

        $response = $this->withHeaders( ['HTTP_REFERER' => '/'] )->actingAs( $user, 'web' )->get( '/' );

        $response->assertStatus( 302 );
        
        $response->assertRedirect( '/home' );
    }

    /**
     * Test controller response status for guest.
     *
     * @return void
     */
    public function testNotLoggedInUserResponseStatus()
    {

        $response = $this->get( '/home' );

        $response->assertStatus( 302 );

        $response->assertLocation( 'login' );
    }
}
