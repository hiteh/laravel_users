<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\UsersRepositoryInterface;

class RootController extends Controller
{
    /**
     * Users repository instance.
     *
     * @var App\Repositories\UsersRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( UsersRepositoryInterface $users )
    {
        $this->middleware('guest');
        $this->users = $users;
    }

    /**
     * Depending on whether there is at least one user, show root or welcome view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( $this->users->hasAny() )
        {
            return view('root');
        } else {
            return view('welcome');
        }
    }
}