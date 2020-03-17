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

Route::middleware('auth:api')->get('/api/user', function (Request $request) {
    return $request->user();
});

/**
 * User
 */

Route::post('register', 'UserController@store');
Route::post('login', 'UserController@login');
// à tester
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user/{id}', 'UserController@show');
    Route::put('user/{id}', 'UserController@update');
    Route::delete('user/{id}', 'UserController@destroy'); // ok
});

/**
 * Profile
 */

Route::apiResource('profiles', 'ProfileController')->middleware('data.transform');

/**
 * Locations
 */

Route::apiResource('locations', 'LocationController');
Route::get('profile/{id}/locations', 'LocationController@userAll');

/**
 * Events
 */

Route::apiResource('events', 'EventController')->middleware('data.transform');
Route::get('profile/{id}/events', 'EventController@userAll')->middleware('data.transform');
