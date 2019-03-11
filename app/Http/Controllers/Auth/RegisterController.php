<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Interfaces\UsersRepositoryInterface;
use App\Interfaces\UserDataValidationInterface;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{

    use RegistersUsers;

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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UsersRepositoryInterface $users, UserDataValidationInterface $validator )
    {
        $this->middleware('guest');
        $this->users = $users;
        $this->validator = $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $this->users->hasAnyUser() ? $data['role'] = 'user' : $data['role'] = 'root';

        $user = $this->users->addUser( $data );

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $data = $this->validator->validateUserRegistrationData( $request->all() );

        event( new Registered( $user = $this->create( $data ) ) );

        $this->guard()->login( $user );

        return $this->registered( $request, $user )
                        ?: redirect($this->redirectPath());
    }

    /**
     * Show the application registration form.
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if ( $this->users->hasAnyUser() )
        {
            return view('auth.register');
        } else {
            return redirect()->route('root');
        }
    }
}
