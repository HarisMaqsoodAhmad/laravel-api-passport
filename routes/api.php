<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('create-user', [UserController::class, 'createUser']);
Route::get('get-users', [UserController::class, 'getUsers']);
Route::get('get-user-detail/{id}', [UserController::class, 'getUserDetail']);

Route::put('update-user/{id}', [UserController::class, 'updateUser']);

Route::delete('delete-user/{id}', [UserController::class, 'deleteUser']);

Route::post('login', [UserController::class, 'login']);