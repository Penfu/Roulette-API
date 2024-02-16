<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BetController;

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

Route::get('users', [UserController::class, 'index']);

Route::get('users/me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::delete('users/me', [UserController::class, 'destroy'])->middleware('auth:sanctum');

Route::patch('users/me/name', [UserController::class, 'updateName'])->middleware('auth:sanctum');
Route::patch('users/me/email', [UserController::class, 'updateEmail'])->middleware('auth:sanctum');
Route::patch('users/me/password', [UserController::class, 'updatePassword'])->middleware('auth:sanctum');
Route::patch('users/me/avatar', [UserController::class, 'updateAvatar'])->middleware('auth:sanctum');
Route::delete('users/me/provider', [SocialiteController::class, 'unlinkProvider'])->middleware('auth:sanctum');

Route::get('users/{user:name}', [UserController::class, 'show']);
Route::get('users/{user:name}/bets', [UserController::class, 'bets']);
Route::get('users/{user:name}/stats', [UserController::class, 'stats']);

Route::apiResource('bets', BetController::class)->only(['store'])->middleware('auth:sanctum');
Route::get('/bets/{bet}/roll', [BetController::class, 'getRoll']);
