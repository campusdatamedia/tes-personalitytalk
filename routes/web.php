<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Guest Capabilities...
Route::group(['middleware' => ['guest']], function(){

	// Home
	Route::get('/', function () {
	   return redirect('login');
	});

	// Login
	Route::get('/login', 'UserLoginController@showLoginForm');
	Route::post('/login', 'UserLoginController@login');

	// Register
	Route::get('/daftar', 'Auth\RegisterController@showRegistrationForm');
	Route::post('/daftar', 'Auth\RegisterController@register');
	
	// Verification
	Route::get('/verification/token/{token}', 'Auth\RegisterController@verification');
});

// Applicant Capabilities...
Route::group(['middleware' => ['user']], function(){
	// Logout
	Route::post('/logout', 'UserLoginController@logout');

	// Dashboard
	Route::get('/dashboard', 'DashboardController@index');

	// Tes
	Route::get('/tes/{path}', 'TesController@tes');
	Route::post('/tes/{path}/store', 'TesController@store');
	
// 	Route::get('/tes/papi/data', 'TesController@dataAnalisisPapikostick');
});