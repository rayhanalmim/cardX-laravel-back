<?php

use App\Http\Controllers\CardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;

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

// Card resource routes
// Route::apiResource('cards', CardController::class);


// Card routes (protected by authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('cards', [CardController::class, 'index']); // Get all cards
    Route::post('cards', [CardController::class, 'store']); // Create a new card
    Route::patch('cards/verify', [CardController::class, 'updateVerification']); // Update verification status
    Route::get('cards/{card}', [CardController::class, 'show']); // Get a specific card
    Route::post('cards/update/{card}', [CardController::class, 'update']); // Update a specific card with POST
    Route::delete('cards/{card}', [CardController::class, 'destroy']); // Delete a specific card
});

// User routes (no authentication required)
Route::get('/user/{id}', [UserController::class, 'getUserById']); // Get user by ID
Route::post('/user/{id}', [UserController::class, 'updateUser']); // Update user by ID


// Authentication routes
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

// Authenticated user route (requires valid token)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
