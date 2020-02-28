<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Interfaces\UsersRepositoryInterface;
use App\Http\Requests\RegisterUser;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{

    use RegistersUsers;

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
    public function __construct( UsersRepositoryInterface $users )
    {
        $this->middleware('guest');
        $this->repository = $users;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register( RegisterUser $request )
    {
        $data = $request->validated();
        $data['role'] = 'user';
        $this->repository->create( $data );
        $response = $this->repository->response();
        extract( $response );

        if( isset( $success ) )
        {
            event( new Registered( $success ) );
            $this->guard()->login( $success );
            return $this->registered( $request, $success )
                        ?: redirect($this->redirectPath());
        }

        if( isset( $error ) )
        {
            redirect()->back()->withErrors( $error );
        }


    }

    /**
     * Show the application registration form.
     *
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return $this->repository->hasAny() ? view('auth.register') : redirect()->route('root');
    }
}
