<?php

use Illuminate\Support\Facades\Route;

Route::post('password/reset', 'Auth\PasswordResetController@sendResetLinkEmail')->name('password.reset');
Route::get('password/find/{token}', 'Auth\PasswordResetController@showResetForm');
Route::put('password/change', 'Auth\PasswordResetController@update');

/* Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
}); */
