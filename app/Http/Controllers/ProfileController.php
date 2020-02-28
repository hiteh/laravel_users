<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfile;
use App\Http\Controllers\Controller;
use App\Interfaces\UsersRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * The users repository instance.
     *
     * @var App/Repositories/UsersRepository;
     */
    protected $users;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UsersRepositoryInterface $users )
    {
    	$this->middleware( 'auth' );
        $this->middleware( 'profile' );
        $this->users = $users;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update( UpdateProfile $request )
    {
        $data = $request->validated();
        $user = $this->users->update( $data, Auth::user()->id );

        return redirect()->route( 'profile' )->with( ['success' => __('profile.update_success_msg') ] );
    }
}
