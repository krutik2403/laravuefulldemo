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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });




Route::post('login', 'Auth\LoginController@loginCheck');
Route::post('register', 'Auth\RegisterController@register');
Route::get('refresh', 'Auth\AuthController@refresh');
Route::get('me', 'Auth\AuthController@user');
Route::post('logout', 'Auth\AuthController@logout');

Route::resource('todo', 'ToDoController');