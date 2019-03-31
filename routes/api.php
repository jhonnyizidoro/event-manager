<?php

//ROTAS PÚBLICAS
Route::post('login', 'AuthController@login');

//ROTAS QUE REQUEREM AUTENTICAÇÃO
Route::middleware('auth')->group(function() {
	Route::post('logout', 'AuthController@logout');
	Route::post('refresh', 'AuthController@refresh');
});

//ROTAS QUE REQUEREM PERMISSÃO DE ADMINISTRADOR
Route::middleware('admin')->group(function() {

});