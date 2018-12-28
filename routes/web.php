<?php

use App\User;

// Language Switch routes
Route::post('/lang', 'LangController@store')->name('lang.store');
Route::get('/lang', 'LangController@index')->name('lang');


Route::middleware(['lang'])->group(function () {
	// Auth routes
	Auth::routes();
	// App routes
	Route::get('/', function (User $user)
		{
			if ( $user->all()->first() )
			{
				return redirect('login');
			} else {
				return view('welcome');
			}
		}
	);
	Route::get('/home', 'HomeController@index')->name('home');
});
