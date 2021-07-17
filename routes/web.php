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

// User Capabilities...
Route::group(['middleware' => ['user']], function(){
	// Logout
	Route::post('/logout', 'UserLoginController@logout');

	// Dashboard
	Route::get('/dashboard', 'DashboardController@index');

	// Tes
	Route::get('/tes/{path}', 'TesController@tes');
	Route::post('/tes/{path}/store', 'TesController@store');
	Route::post('/tes/{path}/delete', 'TesController@delete');
});

// Admin Capabilities...
Route::group(['middleware' => ['admin']], function(){
	// Ringkasan
	Route::get('/admin', 'Admin\RingkasanController@index');

	// Tes
	Route::get('/admin/tes', 'Admin\TesController@index');

	// Paket Soal
	Route::get('/admin/paket-soal', 'Admin\PaketSoalController@index');
	Route::get('/admin/paket-soal/detail/{id}', 'Admin\PaketSoalController@detail');
});