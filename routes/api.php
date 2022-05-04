<?php

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
Route::group(['middleware' => ['web']], function () {
    // your routes here
    Route::get("/users", [UserController::class, 'index']);
});

// Route::middleware('auth:sanctum')->group(function() {

// });

Route::post('/user/create', [UserController::class, 'create']);
Route::post('/login', [UserController::class, 'login']);
