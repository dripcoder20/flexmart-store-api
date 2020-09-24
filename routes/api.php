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
| is assigned the'api' middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('{userId}')->group(function () {
    Route::put('cart', 'Api\CartController@update');
    Route::delete('cart', 'Api\CartController@destroy');
    Route::resource('cart', 'Api\CartController')->only('store', 'index');
    Route::get('transactions/', 'Api\TransactionController@index');
    Route::get('transactions/{trackingNumber}', 'Api\TransactionController@show');
    Route::delete('transactions/{trackingNumber}', 'Api\TransactionController@destroy');
    Route::resource('/orders', 'Api\OrdersController')->only('index');
});
Route::resource('orders', 'Api\OrdersController')->only('store', 'update', 'delete', 'show');
Route::post('orders/{order}/status', 'Api\OrderStatusController@store');
