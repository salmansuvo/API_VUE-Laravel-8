<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

//    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
//    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@authenticate');

});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');
    Route::post('products/list','App\Http\Controllers\ProductController@index');
    Route::post('products','App\Http\Controllers\ProductController@store');
    Route::post('products/{product}/edit','App\Http\Controllers\ProductController@edit');
    Route::put('products/{product}','App\Http\Controllers\ProductController@update');
    Route::delete('products/{product}','App\Http\Controllers\ProductController@destroy');
});
