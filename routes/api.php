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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('cart/{userId}', "Api\CartController@store");
Route::get('cart/{userId}', "Api\CartController@index");
Route::put('cart/{userId}', "Api\CartController@update");
Route::delete('cart/{userId}', "Api\CartController@destroy");
