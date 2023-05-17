<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [PassportAuthController::class, 'login']);
    Route::post('register', [PassportAuthController::class, 'register']);
    Route::group([
        'middleware' => 'auth:api'
      ], function() {
        Route::get('user', [PassportAuthController::class, 'user']);
        Route::get('logout', [PassportAuthController::class, 'logout']);
      });
});

Route::group([
    'prefix' => 'comment',
    'middleware' => [
        'auth:api',
    ]
  ], function() {
    Route::post('/', [CommentController::class, 'store']);
});

Route::group([
    'prefix' => 'news',
    'middleware' => [
        'auth:api',
        'isAdmin'
    ]
  ], function() {
    Route::get('/list/offset/{offset}/limit/{limit}/data', [NewsController::class, 'showAll']);
    Route::post('/', [NewsController::class, 'store']);
    Route::post('/{id}', [NewsController::class, 'update']);
    Route::get('/{id}', [NewsController::class, 'show']);
    Route::delete('/{id}', [NewsController::class, 'destroy']);
});

