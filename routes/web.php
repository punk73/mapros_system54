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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('/', ['uses' => 'MainController@index', 'as' => 'copy'] )->name('copy');

Route::group(['prefix' => 'main'], function($route){
	$route->post('/', 'MainController@post' );
});

Route::group(['prefix' => 'join'], function($route){
	$route->get('/', 'JoinController@index' );
	$route->post('/', 'JoinController@post' );
});

Route::group(['prefix' => 'admin'], function ($route) {
	Voyager::routes();
	$route->get('sequences/{id}/copy', 'SequenceController@showCopy');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'ticket_masters'], function($route){
	$route->post('/', 'MasterTicketController@post' );
});

