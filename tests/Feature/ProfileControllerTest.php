<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileControllerTest extends TestCase
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
     * Test controller response status.
     *
     * @return void
     */
    public function testProfileControllerResponse()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create( ['name' => 'user'] );
        $user->roles()->attach( $role );

        $response = $this->actingAs( $user, 'web' )->get('/users' . '/' . $user->id );

        $response->assertStatus(200);

        $response->assertViewIs('user.profile');

        $response->assertViewHas('data');
    }

    /**
     * Test controller response status.
     *
     * @return void
     */
    public function testProfileControllerResponseForUpdate()
    {
        $user = factory('App\User')->create();
        $role = factory('App\Role')->create(['name' => 'user']);
        $user->roles()->attach( $role );

        Storage::fake('public');

        $newName = $this->faker->name;
        $newEmail = $this->faker->unique()->safeEmail;
        $newPassword = $this->faker->password();
        $newAvatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->withHeaders( ['HTTP_REFERER' => '/users' . '/' . $user->id] )->actingAs($user, 'web')->patch('/users' . '/' . $user->id, [
            'name'                  => $newName,
            'email'                 => $newEmail,
            'password'              => $newPassword,
            'password_confirmation' => $newPassword,
            'avatar'                => $newAvatar,
        ]);

        $this->assertDatabaseHas('users', [
            'name'            => $newName,
            'email'           => $newEmail, 
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/users' . '/' . $user->id );
        $response->assertSessionHas('success');
    }
}
