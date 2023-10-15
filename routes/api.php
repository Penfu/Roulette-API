<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RollController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::get('users/{user:name}', [UserController::class, 'show']);
Route::get('users/{user:name}/bets', [UserController::class, 'bets']);
Route::get('users/{user:name}/stats', [UserController::class, 'stats']);

Route::apiResource('rolls', RollController::class)->only(['index']);

Route::apiResource('bets', BetController::class)->only(['store'])->middleware('auth:sanctum');
Route::get('/bets/{bet}/roll', [BetController::class, 'getRoll']);
