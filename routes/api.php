<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PostController;
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

Route::get('security/csrf', [AccountController::class, 'csrf']);
Route::post('account/login', [AccountController::class, 'login']);
Route::post('account/refresh', [AccountController::class, 'refresh']);

Route::get('blog/{blog}', [BlogController::class, 'show']);
Route::get('blog/{blog}/post', [PostController::class, 'index']);

Route::get('i18n/{locale}.json', [LocaleController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('account/logout', [AccountController::class, 'logout']);
    Route::get('account/current', [AccountController::class, 'index']);
    Route::get('account/sessions', [AccountController::class, 'sessions']);

    Route::apiResources([
        'feed' => 'FeedController',
        'account' => 'AccountController',
        'blog' => 'BlogController',
    ]);

    Route::apiResource('post', 'PostController')
        ->except(['index']);

    Route::post('upload', function (Request $request) {
        // TODO
    });
});
