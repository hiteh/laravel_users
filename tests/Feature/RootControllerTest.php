<?php

namespace Tests\Feature;

use \Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RootControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Create a new test instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();

      $this->mock = Mockery::mock('App\Interfaces\UsersRepositoryInterface');
    }

    /**
     * Clean up Mockery.
     *
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test controller response status.
     *
     * @return void
     */
    public function testRootControllerResponse()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test of the view returned by controller when there are no users.
     *
     * @return void
     */
    public function testRootControllerReturnsWelcomeView()
    {
        $this->mock->shouldReceive('hasAny')->once()->andReturn(false);
        
        $this->app->instance('App\Interfaces\UsersRepositoryInterface', $this->mock);

        $response = $this->get('/');

        $response->assertViewIs('welcome');
    }

    /**
     * Test of the view returned by controller when there is at least one user.
     *
     * @return void
     */
    public function testRootControllerReturnsRootView()
    {
        $this->mock->shouldReceive('hasAny')->once()->andReturn(true);
        
        $this->app->instance('App\Interfaces\UsersRepositoryInterface', $this->mock);

        $response = $this->get('/');

        $response->assertViewIs('root');
    }
}
