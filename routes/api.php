<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

/* Route::get('profile', function () {
    // Only verified users may enter...
})->middleware('verified'); */

Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});

/**
 * User
 */

Route::post('register', 'UserController@store');
Route::post('login', 'UserController@login');
Route::get('user/{id}/email/verified', 'UserController@hasVerifiedEmail');

Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
Route::get('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');

require __DIR__ . '/auth/passwordReset.php';

// à tester
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@get');
    Route::get('user/{id}', 'UserController@show');
    Route::put('user/{id}', 'UserController@update');
    Route::delete('user/{id}', 'UserController@destroy'); // ok
    // Route::post('password/change', 'UserController@changePassword');
});

/**
 * Profile
 */

Route::apiResource('profiles', 'ProfileController')->middleware('data.transform');
Route::get('profile/{id}/privacy', 'ProfilePrivacyController@show');
Route::put('profile/{id}/privacy', 'ProfilePrivacyController@update');

/**
 * Locations
 */

Route::apiResource('locations', 'LocationController');
Route::get('profile/{id}/locations', 'LocationController@userAll');
Route::get('location/{id}/events', 'LocationController@allEvents');

/**
 * Events
 */

Route::apiResource('events', 'EventController')->middleware('data.transform');
Route::get('profile/{id}/events', 'EventController@userAll')->middleware('data.transform');

Route::get('profile/{id}/subscriptions', 'EventController@userAllSubscriptions');

Route::group(['middleware' => 'auth:api'], function () {
    Route::put('events/{eventId}/users/{userId}/subscription', 'EventSubscriptionController@update');
    Route::delete('events/{eventId}/users/{userId}/subscription', 'EventSubscriptionController@destroy');
});

/**
 * Admin
 */
Route::get('ping', 'Admin\ToolsController@ping');
