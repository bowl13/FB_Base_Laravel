<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

Route::get('usuarios/callback', 'UsuariosController@callback');
Route::post('facebook/login', 'UsuariosController@loginFacebook');

Route::resource('usuarios', 'UsuariosController');