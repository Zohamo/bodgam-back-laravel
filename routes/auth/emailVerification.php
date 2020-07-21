<?php

use Illuminate\Support\Facades\Route;

Route::get('user/{id}/email/verified', 'UserController@hasVerifiedEmail');

Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
