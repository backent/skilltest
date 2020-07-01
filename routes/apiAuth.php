<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Auth Routes
|--------------------------------------------------------------------------
|
| Routes that require Authorization
|
*/

Route::get('user', function() {
	return request()->user();
});	

Route::post('logout', 'Auth\AuthController@logout');