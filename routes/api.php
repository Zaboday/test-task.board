<?php

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

Route::get('messages', 'Api\MessagesController@index');
Route::get('messages/{id}', 'Api\MessagesController@show');

Route::middleware('auth.api')->group(
    static function () {
        Route::get('users/me', 'Api\UsersController@profile');
        Route::post('messages', 'Api\MessagesController@store');
        Route::delete('messages/{id}', 'Api\MessagesController@destroy');
    }
);
