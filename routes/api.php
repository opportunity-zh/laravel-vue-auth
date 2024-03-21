<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\TokenController;

use App\Http\Controllers\UserController;

Route::post('/sanctum/token', TokenController::class);

Route::get("/test-me", function () {
    return 'Hallo vom Laravel Backend!';
});


/**
 * AUTH ROUTES
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test', function(){
        return User::findOrFail(auth()->id());
    });
    Route::get('/users/auth', [UserController::class, 'show']);
  });