<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\UserDataValidationInterface;
use App\Interfaces\UsersRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UserDataValidationInterface $validator, UsersRepositoryInterface $users )
    {
    	$this->middleware( 'auth' );
        $this->middleware( 'profile' );
        $this->validator = $validator;
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
    public function update( Request $request )
    {
        $data = $this->validator->validateUserCreationData( $request->all(), Auth::user()->id );
        $user = $this->users->updateUser( Auth::user()->id, $data );

        return redirect()->route( 'profile' )->with( ['success' => __('profile.update_success_msg') ] );
    }
}
