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
				return view('root');
			} else {
				return view('welcome');
			}
		}
	);
	// Home
		//Show
	Route::get('/home', 'HomeController@index')->name('home');
	// Profile
		//Show
	Route::get('/profile', 'ProfileController@index')->name('profile');
		//Patch
	Route::patch('/profile/{id}', 'ProfileController@update')->name('profile.update');
	// Users
		//Show
	Route::get('/users', 'UsersController@index')->name('users');
		//Add
	Route::post('/users', 'UsersController@store')->name('users.store');
		//Edit
	Route::patch('/users/{id}', 'UsersController@update')->name('users.update');
		//Delete
	Route::delete('/users/{id}', 'UsersController@destroy')->name('users.destroy');
});
