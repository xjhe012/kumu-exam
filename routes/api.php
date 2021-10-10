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


Route::post('register', 'Api\RegisterController@store');
Route::post('/login', 'Api\AuthController@login');
Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::apiResource('user', 'Api\UserController');
    Route::post('/register',  'Api\RegisterController@store');
    Route::post('/logout', 'Api\AuthController@logout');
    Route::post('/refresh', 'Api\AuthController@refresh');
    Route::get('/user-profile', 'Api\AuthController@userProfile');   
        
});