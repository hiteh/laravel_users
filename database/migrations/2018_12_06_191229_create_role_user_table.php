<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });

        $this->postCreate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }

    private function postCreate()
    {
        //create basic roles
        if ( App::environment('production') )
        {
            if ( ! Role::where('name', 'root')->first() )
            {
                Role::create([
                    'name'        => 'root',
                    'description' => 'role.description_root'
                ]);
            }

            if ( ! Role::where('name', 'user')->first() )
            {
                Role::create([
                    'name'        => 'user',
                    'description' => 'role.description_user'
                ]);
            }

            if ( ! Role::where('name', 'admin')->first() )
            {
                Role::create([
                    'name'        => 'admin',
                    'description' => 'role.description_admin'
                ]);
            }
        }
    }
}
