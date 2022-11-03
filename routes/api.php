<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::middleware('login')->group(function () {
    Route::get('users', [UserController::class, 'lists']);
    Route::patch('tasks/{task}', [TaskController::class, 'mention']);
    Route::patch('users/{user}', [UserController::class, 'makeAdmin']);

    Route::get('tasks', [TaskController::class, 'lists']);
    Route::post('tasks', [TaskController::class, 'create']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::delete('tasks/{task}', [TaskController::class, 'delete']);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
