<?php

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::domain(config('app.domain'))->group(function () {
//    Route::post('account/login', 'AccountController@login')->name('login');
//    Route::post('account/logout', 'AccountController@logout')->name('logout');
//    Route::get('account/sessions', 'AccountController@sessions')->name('sessions');
//
//    Route::prefix('account')->namespace('Account')->group(function () {
//        Route::get('login', 'LoginController@showLoginForm')->name('login');
//        Route::get('create', 'RegisterController@showRegistrationForm')->name('register');
//        Route::post('create', 'RegisterController@register')->name('register');
//    });
//
//    Route::get('feed', 'FeedController@index')->name('feed');
//});
//
//Route::domain('{blog}.' . config('app.domain'))->group(function () {
//    Route::get('/', 'BlogController@show');
//});
//
//Route::get('/', function () {
//    return view('welcome');
//})->name('home');
