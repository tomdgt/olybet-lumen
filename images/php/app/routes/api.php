<?php

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

Route::get('validate/{personalCode}', 'PersonalCodeController@validateCode');
Route::get('generate/{birthDateString}/{sex}', 'PersonalCodeController@generateCodes');
