<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\AuthController;

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

Route::prefix('auth')->group(function () {
    // Registration Route
    Route::post('register', [AuthController::class, 'register']);
    
    // Login Route
    Route::post('login', [AuthController::class, 'login']);
    
    // Protected routes that require authentication
    Route::middleware('auth:sanctum')->group(function () {
        // Current Authenticated User Info
        Route::get('me', [AuthController::class, 'me']);
        
        // Logout
        Route::post('logout', [AuthController::class, 'logout']);
    });
});


Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/dashboard', function () {
    return ResponseHandler::success('Welcome Admin');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return ResponseHandler::success('Authenticated user info', $request->user()->toArray());
});


 