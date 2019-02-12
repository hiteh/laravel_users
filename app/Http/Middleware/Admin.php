<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $root = Auth::user()->roles()->where( 'name', 'root' )->exists();
        $admin = Auth::user()->roles()->where( 'name', 'admin' )->exists();

        if ( $root || $admin )
        {
            return $next($request); 
        }
        else
        {
           return redirect()->route('home'); 
        }
    }
}
