<?php

//Rotas de autenticação
Route::prefix('auth')->group(function() {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout')->middleware('auth');
	Route::get('refresh', 'AuthController@refresh')->middleware('auth');
});

//Rotas de gerênciamento de usuários
Route::prefix('user')->group(function() {
	Route::get('', 'UserController@index')->middleware('admin');
	Route::post('', 'UserController@store');
	Route::get('me', 'UserController@me')->middleware('auth');
	Route::put('', 'UserController@update')->middleware('auth');
	Route::delete('{user_id}', 'UserController@destroy')->middleware('auth');
	Route::get('address', 'UserController@address')->middleware('auth');
	Route::get('profile', 'UserController@profile')->middleware('auth');
	Route::put('profile', 'UserController@updateProfile')->middleware('auth');
});

//Rotas de gerênciamento de endereço
Route::prefix('address')->group(function() {
	Route::put('user', 'AddressController@updateUserAddress')->middleware('auth');
	Route::put('event', 'AddressController@updateEventAddress')->middleware('auth');
});

//Rotas para gerenciamento das cidades
Route::prefix('city')->group(function() {
	Route::get('', 'CityController@index');
	Route::post('', 'CityController@store')->middleware('admin');
	Route::get('{city_id}', 'CityController@show');
	Route::put('', 'CityController@update')->middleware('admin');
	Route::delete('{city_id}', 'CityController@destroy')->middleware('admin');
});

//Rotas para gerenciamento de estados
Route::prefix('state')->group(function() {
	Route::get('', 'StateController@index');
	Route::post('', 'StateController@store')->middleware('admin');
	Route::get('{state_id}', 'StateController@show');
	Route::put('', 'StateController@update')->middleware('admin');
	Route::delete('{state_id}', 'StateController@destroy')->middleware('admin');
	Route::get('{state_id}/cities', 'StateController@cities');
});

//Rotas para gerenciamento das categorias
Route::prefix('category')->group(function() {
	Route::get('', 'CategoryController@index');
	Route::post('', 'CategoryController@store')->middleware('admin');
	Route::get('{category_id}', 'CategoryController@show');
	Route::put('', 'CategoryController@update')->middleware('admin');
	Route::delete('{category_id}', 'CategoryController@destroy')->middleware('admin');
});

//Rotas de gerênciamento de eventos
Route::prefix('event')->group(function() {
	Route::get('', 'EventController@index')->middleware('auth');
	Route::post('', 'EventController@store')->middleware('auth');
	Route::get('{event_id}', 'EventController@show');
	Route::put('', 'EventController@update')->middleware('auth');
	Route::delete('{event_id}', 'EventController@destroy')->middleware('auth');
});

//Rotdas para gerenciamentos de certificados
Route::prefix('certificate')->group(function() {
	Route::get('', 'CertificateController@index')->middleware('auth');
	Route::put('', 'CertificateController@update')->middleware('auth');
});

//Rotas para gerenciamento de equipes de administradores
Route::prefix('state')->group(function() {
	Route::get('', 'StateController@index');
	Route::post('', 'StateController@store')->middleware('admin');
	Route::get('{state_id}', 'StateController@show');
	Route::put('', 'StateController@update')->middleware('admin');
	Route::delete('{state_id}', 'StateController@destroy')->middleware('admin');
});

Route::prefix('staff')->group(function() {
	Route::get('', 'StaffController@index')->middleware('auth');
	Route::post('', 'StaffController@store')->middleware('auth');
	Route::delete('{staff_id}', 'StaffController@destroy')->middleware('auth');
});

Route::prefix('profile')->group(function() {
	Route::put('', 'UserProfileController@update')->middleware('auth');
});