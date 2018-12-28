<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
    	// Check if language switch form is send
    	if( $request->isMethod('post') && $request->get('lang') )
    	{	
    		//Set session language
    		$request->session()->put('lang', $request->get('lang'));
    	}
        // redirect back
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // redirect back
        return redirect()->route('home');
    }
}
