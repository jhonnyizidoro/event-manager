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
	Route::put('', 'UserController@update')->middleware('owner');
	Route::delete('{user_id}', 'UserController@destroy')->middleware('owner');
});

//Rotas de gerênciamento de endereço
Route::prefix('address')->group(function() {
	Route::put('user', 'AddressController@updateUserAddress')->middleware('owner');
});

//Rotas para gerenciamento das cidades
Route::prefix('city')->group(function() {
	Route::get('', 'CityController@index');
	Route::post('', 'CityController@store')->middleware('admin');
	Route::get('{city_id}', 'CityController@show');
	Route::put('', 'CityController@update')->middleware('admin');
	Route::delete('{city_id}', 'CityController@destroy')->middleware('admin');
});

Route::prefix('category')->group(function() {
	Route::get('', 'CategoryController@index');
	Route::post('', 'CategoryController@store')->middleware('admin');
	Route::get('{category_id}', 'CategoryController@show');
	Route::put('', 'CategoryController@update')->middleware('admin');
	Route::delete('{category_id}', 'CategoryController@destroy')->middleware('admin');
});