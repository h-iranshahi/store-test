<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\ResponseHandler;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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



// Product Management - Admin
//-----------------------------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/admin/products', [AdminProductController::class, 'create']); 
    Route::put('/admin/products/{id}', [AdminProductController::class, 'update']); 
    Route::delete('/admin/products/{id}', [AdminProductController::class, 'destroy']); 
});

// Product Management - User
//-----------------------------------
Route::middleware('auth:sanctum')->get('/products', [ProductController::class, 'index']);


// Place Order - User
//-----------------------------------
Route::middleware('auth:sanctum')->post('/order', [OrderController::class, 'placeOrder']);
