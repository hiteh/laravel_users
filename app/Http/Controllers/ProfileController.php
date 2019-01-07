<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\User;


class ProfileController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('auth');
    }
    
    /**
     * Show user's profile.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        if ( Auth::user()->id == $id ) 
        {
            return view( 'user.profile', ['user' => Auth::user()] );
        } 
        else
        {
            return redirect('/');
        }
        
    }

    /**
     * Update user's profile.
     *
     * @param string $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update( $id, Request $request )
    {
        if ( Auth::user()->id == $id ) 
        {
            $data = $this->validator($request->all(), $id)->validate();

            if ( ! empty($data['name']) )
            {
                Auth::user()->update([
                    'name'  => $data['name'],
                ]);
            }

            if ( ! empty($data['email']) )
            {
                Auth::user()->update([
                    'email' => $data['email'],
                ]);
            }

            if ( ! empty($data['password']) )
            {
                Auth::user()->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            return redirect()->route( 'profile', $id )->with( ['success' => 'profile.update_success_msg'] );
        } 
        else
        {
            return redirect()->route( 'profile', $id )->with( ['success' => 'profile.invalid_user_msg'] );
        }
    }

    /**
     * Request validator.
     *
     * @param  array $data
     * @param  string $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator( array $data, $id = null )
    {
        return Validator::make($data, [
            'name'     => ['sometimes','required', 'string', 'max:255'],
            'email'    => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users,email,'.$id ],
            'password' => ['sometimes','required', 'string', 'min:6', 'confirmed'],
        ]);
    }
}
