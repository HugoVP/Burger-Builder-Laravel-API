<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

Route::get('/ingredients', function () {
  return response()->json([
    'salad' => 0,
    'bacon' => 0,
    'cheese' => 0,
    'meat' => 0,
  ], 200);
});

Route::get('/orders', 'OrderController@index');
Route::get('/orders/{id}', 'OrderController@show');
Route::post('/orders', 'OrderController@store');
