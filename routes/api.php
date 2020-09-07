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

Route::post('{userId}/cart', "Api\CartController@store");
Route::get('{userId}/cart', "Api\CartController@index");
Route::put('{userId}/cart', "Api\CartController@update");
Route::delete('{userId}/cart', "Api\CartController@destroy");
Route::get('{userId}/transactions/', "Api\TransactionController@index");
Route::get('{userId}/transactions/{trackingNumber}', "Api\TransactionController@show");
Route::delete('{userId}/transactions/{trackingNumber}', "Api\TransactionController@destroy");
