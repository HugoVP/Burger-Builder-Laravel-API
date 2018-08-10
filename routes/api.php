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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ingredients', function () {
  return response()->json([
    'salad' => 0,
    'bacon' => 0,
    'cheese' => 0,
    'meat' => 0,
  ], 200);
});

Route::group([
  'middleware' => ['api'],
  'prefix' => 'orders',
], function ($router) {
  Route::get('/', 'OrderController@index');
  Route::get('/{id}', 'OrderController@show');
  Route::post('/', 'OrderController@store');
});

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth',
], function ($router) {
  Route::post('signup', 'AuthController@signup');
  Route::post('login', 'AuthController@login');
  Route::post('logout', 'AuthController@logout');
  Route::post('refresh', 'AuthController@refresh');
  Route::post('me', 'AuthController@me');
});