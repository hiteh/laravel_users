<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\Gate;
use App\Interfaces\UsersRepositoryInterface;

class UsersController extends Controller
{
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
    public function __construct( UsersRepositoryInterface $users )
    {
        $this->middleware( 'auth' );
        $this->repository = $users;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index( Request $request, User $user, $id = null )
    {

        $this->authorize( 'view', $user );   

        if( isset( $id ) )
        {
            $this->repository->read( $id );
            $message = $this->repository->response();
            extract( $message );
            $view = isset( $success ) ? view( 'user.profile', ['data' => $success->toArray()] ) : $message;

            return isset( $error ) || isset( $warning ) ? abort( 404,'Page not found' ) : $view;
        }

        $users = $this->repository->paginated( 5, $request->query(), $request->url(), 'id' );
        $roles = $this->repository->roles()->pluck( 'name', 'name' );

        return view( 'user.users', [
            'data' => $users,
            'roles' => $roles,
        ] );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( CreateUser $request, User $user )
    {

        $this->authorize( 'create', $user );
        $data = $request->validated();
        $this->repository->create( $data );
        $response = $this->repository->response();
        extract( $response );
        $response = isset( $success ) ? ['success' => __( 'messages.user_created_msg', ['name' => $success->name] )] : $response;

        return isset( $error ) ? redirect()->back()->withErrors( $response ) : redirect()->back()->with( $response );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update( $id, UpdateUser $request, User $user )
    {   
        $this->authorize( 'update', $user );
        $data = $request->validated();
        $this->repository->update( $data, $id );
        $response = $this->repository->response();
        extract( $response );
        $response = isset( $success ) ? ['success' => __( 'messages.user_updated_msg', ['name' => $success->name] )] : $response;

        return isset( $error ) ? redirect()->back()->withErrors( $response ) : redirect()->back()->with( $response );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id, User $user )
    {
        $this->authorize( 'delete', $user );
        $this->repository->delete( $id );
        $response = $this->repository->response();
        extract( $response );
        $response = isset( $success ) ? ['success' => __( 'messages.user_deleted_msg', [ 'name' => $success->name ] )] : $response;

        return isset( $error ) ? redirect()->back()->withErrors( $response ) : redirect()->back()->with( $response );
    }
}
