<?php

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

/**
 * User
 */

Route::post('register', 'UserController@store');
Route::post('login', 'UserController@login');

require __DIR__ . '/auth/emailVerification.php';
require __DIR__ . '/auth/passwordReset.php';

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'UserController@get');
    // Route::get('users/{id}', 'UserController@show');
    // Route::put('users/{id}', 'UserController@update');
    Route::delete('users/{id}', 'UserController@destroy');
    // Route::post('password/change', 'UserController@changePassword');

    Route::get('user/notifications', 'NotificationController@user');
    Route::get('user/notifications/unread', 'NotificationController@userUnread');
    Route::get('user/notifications/{id}/read', 'NotificationController@read');
});


/**
 * Events
 */

Route::get('events', 'EventController@index')->middleware('data.transform');

Route::group(['middleware' => ['auth:api', 'verified', 'data.transform']], function () {
    Route::get('events/{id}', 'EventController@show');
    Route::post('events', 'EventController@store');
    Route::put('events/{id}', 'EventController@update');
    Route::delete('events/{id}', 'EventController@destroy');
});

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::put('events/{eventId}/users/{userId}/subscription', 'EventSubscriptionController@update');
    Route::delete('events/{eventId}/users/{userId}/subscription', 'EventSubscriptionController@destroy');
    Route::get('profiles/{id}/events', 'EventController@userAll')->middleware('data.transform');
    Route::get('profiles/{id}/subscriptions', 'EventController@userAllSubscriptions');
});

/**
 * Profiles
 */

Route::group(['middleware' => ['auth:api', 'data.transform']], function () {
    Route::get('profiles', 'ProfileController@index')->middleware('verified');
    Route::get('profiles/{id}', 'ProfileController@show');
    Route::put('profiles/{id}', 'ProfileController@update');
    // Route::get('profiles/{id}/privacy', 'ProfilePrivacyController@show');
    Route::put('profiles/{id}/privacy', 'ProfilePrivacyController@update');
});

/**
 * Locations
 */

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::apiResource('locations', 'LocationController');
    Route::get('profiles/{id}/locations', 'LocationController@userAll');
    Route::get('locations/{id}/events', 'LocationController@allEvents');
});

/**
 * Admin
 */

Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::get('admin/ping', 'Admin\ToolsController@ping');
    Route::post('admin/push', 'Admin\ToolsController@push');
});
