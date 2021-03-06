<?php

//Rotas de autenticação
Route::prefix('auth')->group(function() {
	Route::post('login', 'AuthController@login');
	Route::get('logout', 'AuthController@logout')->middleware('auth');
	Route::get('refresh', 'AuthController@refresh')->middleware('auth');
});

//Rotas de gerênciamento de usuários
Route::prefix('user')->group(function() {
	Route::get('get/{search?}', 'UserController@index')->middleware('admin');
	Route::post('', 'UserController@store');
	Route::get('me', 'UserController@me')->middleware('auth');
	Route::put('', 'UserController@update')->middleware('auth');
	Route::delete('{user_id}', 'UserController@destroy')->middleware('auth');
	Route::put('password', 'UserController@updatePassword')->middleware('auth');
	Route::put('password/reset', 'UserController@resetPassword')->middleware('admin');

	Route::get('address', 'UserController@address')->middleware('auth');
	Route::get('profile', 'UserController@profile')->middleware('auth');
	Route::get('{user_id}/profile', 'UserController@profile')->middleware('auth');
	Route::put('profile', 'UserController@updateProfile')->middleware('auth');

	Route::get('search/email/{email}', 'UserController@searchByEmail')->middleware('auth');
	Route::get('find-by-email', 'UserController@findByEmail')->middleware('auth');

	Route::get('followers', 'UserController@getFollowers')->middleware('auth');
	Route::get('followings', 'UserController@getFollowings')->middleware('auth');
	Route::put('{user_id}/unfollow', 'UserController@unfollow')->middleware('auth');
	Route::put('{user_id}/follow', 'UserController@follow')->middleware('auth');

	Route::post('fcm-web-token', 'UserController@saveFcmWebToken')->middleware('auth');
	Route::post('fcm-mobile-token', 'UserController@saveFcmMobileToken')->middleware('auth');

	Route::get('notifications', 'UserController@notifications')->middleware('auth');

	Route::get('posts', 'UserController@posts')->middleware('auth');

	Route::get('events', 'UserController@events')->middleware('auth');
	Route::get('events-followed', 'UserController@followedEvents')->middleware('auth');
	Route::get('events-managed', 'UserController@managedEvents')->middleware('auth');
	Route::get('events-subscribed', 'UserController@subscribedEvents')->middleware('auth');

	Route::get('{user_id}/events', 'UserController@events');
	Route::get('{user_id}/followings', 'UserController@getFollowings')->middleware('auth');
	Route::get('{user_id}/followers', 'UserController@getFollowers')->middleware('auth');
	Route::get('{user_id}/posts', 'UserController@posts')->middleware('auth');
	Route::get('dashboard', 'UserController@dashboard')->middleware('auth');
	Route::get('discover', 'UserController@discover')->middleware('auth');
});

//Rotas de gerênciamento de endereço
Route::prefix('address')->group(function() {
	Route::put('user', 'AddressController@updateUserAddress')->middleware('auth');
	Route::put('event', 'AddressController@updateEventAddress')->middleware('auth');
	Route::put('{id}', 'AddressController@update')->middleware('auth');
});

//Rotas para gerenciamento das cidades
Route::prefix('city')->group(function() {
	Route::get('{search?}', 'CityController@index');
	Route::post('', 'CityController@store')->middleware('admin');
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
	Route::put('{event_id}/follow', 'EventController@follow')->middleware('auth');
	Route::put('{id}/post', 'EventController@addPost')->middleware('auth');
	Route::put('{id}/subscribe', 'EventController@subscribe')->middleware('auth');
	Route::put('{id}/checkin', 'EventController@checkin')->middleware('auth');
	Route::put('{id}/checkout', 'EventController@checkout')->middleware('auth');
	Route::get('{id}/posts', 'EventController@listPosts')->middleware('auth');
});

//Rotdas para gerenciamentos de certificados
Route::prefix('certificate')->group(function() {
	Route::get('', 'CertificateController@index')->middleware('auth');
	Route::put('', 'CertificateController@update')->middleware('auth');

	Route::get('{subscription_id}/download', 'CertificateController@download');

	Route::get('user', 'CertificateController@userCertificates')->middleware('auth');
	Route::get('{user_id}/user', 'CertificateController@userCertificates')->middleware('auth');
	Route::get('validate/{code}', 'CertificateController@validateCertificate');
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
	Route::get('{id}/members', 'StaffController@members')->middleware('auth');
	Route::put('{id}/user', 'StaffController@addMember')->middleware('auth');
	Route::delete('{id}/user/{user_id}', 'StaffController@removeMember')->middleware('auth');
});

Route::prefix('profile')->group(function() {
	Route::put('', 'UserProfileController@update')->middleware('auth');
	Route::put('{id}/post', 'UserProfileController@addPost')->middleware('auth');
	Route::get('{id}/posts', 'UserProfileController@posts')->middleware('auth');
});

Route::prefix('post')->group(function() {
	Route::get('{id}', 'PostController@index')->middleware('auth');
	Route::get('', 'PostController@getPosts')->middleware('auth');
	Route::post('', 'PostController@store')->middleware('auth');
	Route::put('{id}', 'PostController@update')->middleware('auth');
	Route::post('{id}/comment', 'PostController@addComment')->middleware('auth');
	Route::get('{id}/comment', 'PostController@comments')->middleware('auth');
	Route::put('{id}/like', 'PostController@like')->middleware('auth');
});

Route::prefix('serie')->group(function() {
	Route::get('', 'EventSerieController@index')->middleware('auth');
	Route::post('', 'EventSerieController@store')->middleware('auth');
	Route::delete('{serie_id}', 'EventSerieController@destroy')->middleware('auth');
});

Route::prefix('interest')->group(function() {
	Route::get('', 'UserController@myInterests')->middleware('auth');
	Route::post('', 'UserController@addInterest')->middleware('auth');
	Route::delete('{category_id}', 'UserController@deleteInterest')->middleware('auth');
	Route::get('search', 'CategoryController@searchInterest')->middleware('auth');
});

Route::get('search', 'SearchController@search')->middleware('auth');

Route::prefix('notification')->group(function() {
	Route::post('read-all', 'NotificationController@readAll')->middleware('auth');
	Route::put('{id}/read', 'NotificationController@read')->middleware('auth');
});

Route::prefix('comment')->group(function() {
	Route::post('{id}/reply', 'CommentController@addReply')->middleware('auth');
	Route::put('{id}/like', 'CommentController@like')->middleware('auth');
});

Route::get('is-email-valid', 'UserProfileController@isEmailValid')->middleware('auth');