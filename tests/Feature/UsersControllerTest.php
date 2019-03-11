<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
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

    /**
     * Test users controller response for root user.
     *
     * @return void
     */
    public function testUsersControllerResponseForRootUser()
    {
        $rootUser = factory('App\User')->create();
        $rootRole = factory('App\Role')->create(['name' => 'root']);

        $rootUser->roles()->attach( $rootRole );

        $role = factory('App\Role')->create(['name' => 'user']);

        for ( $i = 0; $i <= 3; $i ++ ) {

            $user = factory('App\User')->create();

            $user->roles()->attach( $role );
        }
        
        $response = $this->actingAs($rootUser, 'web')->get('/users');

        $response->assertStatus(200);

        $response->assertViewIs('user.users');

        $response->assertViewHas('users');

        $response->assertViewHas('roles');

    }

    /**
     * Test users controller response for admin user.
     *
     * @return void
     */
    public function testUsersControllerResponseForAdminUser()
    {
        $adminUser = factory('App\User')->create();
        $adminRole = factory('App\Role')->create(['name' => 'admin']);

        $adminUser->roles()->attach( $adminRole );

        $role = factory('App\Role')->create(['name' => 'user']);

        for ( $i = 0; $i <= 3; $i ++ ) {

            $user = factory('App\User')->create();

            $user->roles()->attach( $role );
        }
        
        $response = $this->actingAs($adminUser, 'web')->get('/users');

        $response->assertStatus(200);

        $response->assertViewIs('user.users');

        $response->assertViewHas('users');

        $response->assertViewHas('roles');

    }

    /**
     * Test users controller response for regular user.
     *
     * @return void
     */
    public function testUsersControllerResponseForRegularUser()
    {
        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );
        
        $response = $this->actingAs($regularUser, 'web')->get('/users');

        $response->assertStatus(302);
        $response->assertSessionMissing('success');
        $response->assertRedirect('home');

    }

    /**
     * Test users controller store response for root user.
     *
     * @return void
     */
    public function testUsersControllerStoreResponseForRootUser()
    {
        factory('App\Role')->create(['name' => 'user']);
        $rootUser = factory('App\User')->create();
        $rootRole = factory('App\Role')->create(['name' => 'root']);

        $rootUser->roles()->attach( $rootRole );

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();

        $response = $this->actingAs($rootUser)->post('/users', [
            'name'                  => $name,
            'email'                 => $email,
            'role'                  => 'user',
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'name'            => $name,
            'email'           => $email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller store response for admin user.
     *
     * @return void
     */
    public function testUsersControllerStoreResponseForAdminUser()
    {
        factory('App\Role')->create(['name' => 'user']);
        $adminUser = factory('App\User')->create();
        $adminRole = factory('App\Role')->create(['name' => 'admin']);

        $adminUser->roles()->attach( $adminRole );

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();

        $response = $this->actingAs($adminUser)->post('/users', [
            'name'                  => $name,
            'email'                 => $email,
            'role'                  => 'user',
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'name'            => $name,
            'email'           => $email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller store response for regular user.
     *
     * @return void
     */
    public function testUsersControllerStoreResponseForRegularUser()
    {
        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );
        
        $response = $this->actingAs($regularUser, 'web')->post('/users');

        $response->assertStatus(302);
        $response->assertSessionMissing('success');
        $response->assertRedirect('home');

    }

    /**
     * Test users controller update response for root user.
     *
     * @return void
     */
    public function testUsersControllerUpdateResponseForRootUser()
    {
        $rootUser = factory('App\User')->create();
        $rootRole = factory('App\Role')->create(['name' => 'root']);

        $rootUser->roles()->attach( $rootRole );

        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();

        $response = $this->actingAs($rootUser)->patch('/users/'.$regularUser->id, [
            'name'                  => $name,
            'email'                 => $email,
            'role'                  => 'user',
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'name'            => $name,
            'email'           => $email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller update response for admin user.
     *
     * @return void
     */
    public function testUsersControllerUpdateResponseForAdminUser()
    {
        $adminUser = factory('App\User')->create();
        $adminRole = factory('App\Role')->create(['name' => 'admin']);

        $adminUser->roles()->attach( $adminRole );

        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();

        $response = $this->actingAs($adminUser)->patch('/users/'.$regularUser->id, [
            'name'                  => $name,
            'email'                 => $email,
            'role'                  => 'user',
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'name'            => $name,
            'email'           => $email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller update response for regular user.
     *
     * @return void
     */
    public function testUsersControllerUpdateResponseForRegularUser()
    {
        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );

        $anotherRegularUser = factory('App\User')->create();

        $anotherRegularUser->roles()->attach( $regularRole );

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password();
        
        $response = $this->actingAs($regularUser)->patch('/users/'.$anotherRegularUser->id, [
            'name'                  => $name,
            'email'                 => $email,
            'role'                  => 'user',
            'password'              => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing('success');
        $response->assertRedirect('home');
    }

    /**
     * Test users controller delete response for root user.
     *
     * @return void
     */
    public function testUsersControllerDeleteResponseForRootUser()
    {
        $rootUser = factory('App\User')->create();
        $rootRole = factory('App\Role')->create(['name' => 'root']);

        $rootUser->roles()->attach( $rootRole );

        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );
        
        $response = $this->actingAs($rootUser)->delete('/users/'.$regularUser->id);

        $this->assertDatabaseMissing('users', [
            'name'            => $regularUser->name,
            'email'           => $regularUser->email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller delete response for admin user.
     *
     * @return void
     */
    public function testUsersControllerDeleteResponseForAdminUser()
    {
        $adminUser = factory('App\User')->create();
        $adminRole = factory('App\Role')->create(['name' => 'admin']);

        $adminUser->roles()->attach( $adminRole );

        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );
        
        $response = $this->actingAs($adminUser)->delete('/users/'.$regularUser->id);

        $this->assertDatabaseMissing('users', [
            'name'            => $regularUser->name,
            'email'           => $regularUser->email, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('users');
        $response->assertSessionHas('success');
    }

    /**
     * Test users controller delete response for regular user.
     *
     * @return void
     */
    public function testUsersControllerDeleteResponseForRegularUser()
    {
        $regularUser = factory('App\User')->create();
        $regularRole = factory('App\Role')->create(['name' => 'user']);

        $regularUser->roles()->attach( $regularRole );

        $anotherRegularUser = factory('App\User')->create();

        $anotherRegularUser->roles()->attach( $regularRole );
        
        $response = $this->actingAs($regularUser)->delete('/users/'.$anotherRegularUser->id);

        $this->assertDatabaseHas('users', [
            'name'            => $anotherRegularUser->name,
            'email'           => $anotherRegularUser->email, 
        ]);

        $response->assertStatus(302);
        $response->assertSessionMissing('success');
        $response->assertRedirect('home');
    }
}
