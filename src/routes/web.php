<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'AtCoderController@get');
Route::post('/', 'AtCoderController@submit');
