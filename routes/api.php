<?php

//Rotas de autenticação
Route::prefix('auth')->group(function() {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout')->middleware('auth');
	Route::get('refresh', 'AuthController@refresh')->middleware('auth');
});

//Rotas de gerênciamento de usuários
Route::prefix('user')->group(function() {
	Route::post('', 'UserController@store');
	Route::get('', 'UserController@index')->middleware('admin');
	Route::get('me', 'UserController@me')->middleware('auth');
	Route::put('', 'UserController@update')->middleware('owner');
	Route::delete('{user_id}', 'UserController@destroy')->middleware('owner');
});

//Rotas de gerênciamento de endereço
Route::prefix('address')->group(function() {
	Route::put('user', 'AddressController@updateUserAddress')->middleware('owner');
});