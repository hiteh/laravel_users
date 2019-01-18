<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( 'user.users', [
        	'users' => User::orderBy('created_at', 'desc')->paginate(10),
        	'roles' => Role::where('name','<>','root')->pluck('name')
    	] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validator($request->all())->validate();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach( Role::where('name', $data['role'] )->first() );

        return redirect()->route('users')->with(['success' => __( 'users.user_created_msg', [ 'name' => $user->name ] )]);
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

        $data = $this->validator($request->all(), $id)->validate();
        $user = User::all()->find( $id );

        if ( $user )
        {
            if ( $user->roles()->where( 'name', 'root' )->get()->first() )
            {
                if ( ! empty($data['role']) )
                {
                    unset($data['role']);
                }
            }

            foreach ( $data as $field => $value )
            {
                if ( 'password' === $field )
                {
                    $user->update([
                        $field => Hash::make( $value ),
                    ]);
                }
                else
                {
                    $user->update([
                        $field => $value,
                    ]);
                }
            }

            return redirect()->route('users')->with(['success' => __( 'users.user_updated_msg', [ 'name' => $user->name ] )]);
        }
        else
        {
            return redirect()->route('users')->with(['warning' => __( 'users.user_invalid_msg', [ 'name' => $user->name ] )]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        if ( Gate::allows('delete-user',$id) )
        {
            $user = User::all()->find( $id );

            if ( $user ) 
            {
                $user->roles()->detach();
                $user->delete();

                return redirect()->route('users')->with(['success' => __( 'users.user_deleted_msg', [ 'name' => $user->name ] )]);
            } 
            else
            {
                return redirect()->route('users')->with(['warning' => __( 'users.user_invalid_msg' )]);
            }
        }
        else
        {
            return redirect()->route('users')->with(['warning' => __( 'users.forbidden_operation_msg' )]);
        }
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
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id ],
            'password' => ['sometimes','required', 'string', 'min:6', 'confirmed'],
            'role'     => ['sometimes','required', 'string', Rule::in( Role::where('name','<>','root')->pluck('name') ) ],
        ]);
    }

}
