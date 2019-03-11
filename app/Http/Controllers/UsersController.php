<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Interfaces\UsersRepositoryInterface;
use App\Interfaces\RolesRepositoryInterface;
use App\Interfaces\UserDataValidationInterface;

class UsersController extends Controller
{
    /**
     * The validator service instance.
     *
     * @var App\Services\UserDataValidationService;
     */
    protected $validator;

    /**
     * The users repository instance.
     *
     * @var App/Repositories/UsersRepository;
     */
    protected $users;

    /**
     * The roles repository instance.
     *
     * @var App/Repositories/RolesRepository;
     */
    protected $roles;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UsersRepositoryInterface $users, RolesRepositoryInterface $roles, UserDataValidationInterface $validator )
    {
        $this->middleware( 'auth' );
        $this->middleware( 'admin' );
        $this->users = $users;
        $this->roles = $roles;
        $this->validator = $validator;
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
        	'roles' => $this->roles->getAvailableRolesList(),
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
            $data = $this->validator->validateUserCreationData( $request->all() );
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
            $data = $this->validator->validateUserCreationData( $request->all(), $id );
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
}
