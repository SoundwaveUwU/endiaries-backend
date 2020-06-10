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

Route::post('account/login', 'AccountController@login');
Route::get('account/sessions', 'AccountController@sessions');
Route::post('account/refresh', 'AccountController@refresh');

Route::apiResources([
    'account' => 'AccountController',
]);
