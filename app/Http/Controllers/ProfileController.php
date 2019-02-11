<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware( 'auth' );
        $this->middleware( 'profile' );
    }
    
    /**
     * Show user's profile.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view( 'user.profile', ['user' => Auth::user()] );
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
        $data = $this->validator( $request->all(), $id )->validate();
        $user = Auth::user();

        if ( $user )
        {
            foreach ( $data as $field => $value )
            {
                if ( 'password' === $field )
                {
                    $user->update([
                        $field => Hash::make( $value ),
                    ]);
                }
                else if ( 'avatar' === $field )
                {
                    $value->store('public');

                    $user->update([
                        'avatar' => $value->hashName(),
                    ]);
                }
                else
                {
                    $user->update([
                        $field => $value,
                    ]);
                }

            }

            return redirect()->route( 'profile', $id )->with( ['success' => __('profile.update_success_msg') ] );
        }
        else
        {
            return redirect()->route( 'profile', $id )->with( ['warning' => __('profile.invalid_user_msg') ] );
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
        return Validator::make( $data, [
            'name'     => ['sometimes','required', 'string', 'max:255'],
            'email'    => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users,email,'.$id ],
            'password' => ['sometimes','required', 'string', 'min:6', 'confirmed'],
            'avatar'   => ['sometimes', 'required', 'image'],
        ] );
    }
}
