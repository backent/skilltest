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

Route::get('/soal/{nomor}', 'Soal\SoalController@soal');

Route::get('product', 'Product\ProductController@get');
Route::get('product/{key}', 'Product\ProductController@get');
Route::post('product', 'Product\ProductController@store');
Route::put('product/{key}', 'Product\ProductController@update');
Route::delete('product/{key}', 'Product\ProductController@delete');