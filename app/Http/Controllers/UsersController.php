<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Interfaces\UsersRepositoryInterface;
use App\Http\Middleware\Admin;
use App\User;
use App\Role;

class UsersController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UsersRepositoryInterface $users )
    {
        $this->middleware( 'auth' );
        $this->middleware( 'admin' );
        $this->users = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( 'user.users', [
            'users' => $this->users->getAllUsers(),
        	'roles' => Role::where( 'name','<>','root' )->pluck( 'name' )
    	] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if ( Gate::allows( 'create-user' ) )
        {
            $data = $this->validator( $request->all() )->validate();

            $user = $this->users->addUser( $data );

            return redirect()->route('users')->with( ['success' => __( 'users.user_created_msg', [ 'name' => $user->name ] )] );
        }

        return redirect()->route( 'users' )->with( ['warning' => __( 'users.forbidden_operation_msg' )] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update( $id, Request $request )
    {        
        if ( Gate::allows( 'update-user',$id ) ) 
        {
            $data = $this->validator( $request->all(), $id )->validate();
            $user = $this->users->updateUser( $id, $data );

            return redirect()->route( 'users' )->with( ['success' => __( 'users.user_updated_msg', ['name' => $user->name] )] );
        }

        return redirect()->route( 'users' )->with( ['warning' => __( 'users.forbidden_operation_msg' )] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        if ( Gate::allows( 'delete-user', $id ) )
        {
            $user = $this->users->deleteUser( $id );

            return redirect()->route( 'users' )->with( ['success' => __( 'users.user_deleted_msg', ['name' => $user->name ] )] );
        }

        return redirect()->route( 'users' )->with( ['warning' => __( 'users.forbidden_operation_msg' )] );

    }
    
    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @param  string $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = null)
    {
        return Validator::make( $data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id ],
            'password' => ['sometimes','required', 'string', 'min:6', 'confirmed'],
            'role'     => ['sometimes','required', 'string', Rule::in( Role::where( 'name','<>','root' )->pluck( 'name' ) ) ],
        ] );
    }

}
