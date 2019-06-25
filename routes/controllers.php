<?php

// Outlook Authentication & Authorization
Route::get('outlook/signin', 'Oauth\AuthController@signin')->name('outlook.signin');
Route::get('/authorize', 'Oauth\AuthController@gettoken');
