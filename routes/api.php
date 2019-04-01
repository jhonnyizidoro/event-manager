<?php

//Rotas de autenticação
Route::prefix('auth')->group(function() {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout')->middleware('auth');
	Route::get('refresh', 'AuthController@refresh')->middleware('auth');
});

//Rotas de gerênciamento de usuários
Route::prefix('users')->group(function() {
	Route::post('', 'UserController@store');
	Route::get('', 'UserController@index')->middleware('admin');
	Route::get('me', 'UserController@me')->middleware('auth');
	Route::put('', 'UserController@update')->middleware('auth');
	Route::delete('{id}', 'UserController@destroy')->middleware('auth');
});