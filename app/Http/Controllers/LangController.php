<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateLang;

class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store( UpdateLang $request )
    {
    	// Check if language switch form was send.
    	if( $request->isMethod('post') && $request->get('lang') )
    	{	
    		//Set session language.
    		$request->session()->put('lang', $request->get('lang'));
    	}
        // redirect back.
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
        return redirect()->back();
    }
}
