<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('v1')->group(function () {
// List users
    Route::get('/users', 'v1\UserController@index');
// Get single user
    Route::get('/user/{id}', 'v1\UserController@show');
//Update single user
    Route::put('/user/{id}/update', 'v1\UserController@update');
//Delete user
    Route::delete('/user/{id}', 'v1\UserController@destroy');
});

