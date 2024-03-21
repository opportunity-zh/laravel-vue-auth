<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Http\Controllers\TokenController;

Route::post('/sanctum/token', TokenController::class);

Route::get("/test-me", function () {
    return 'Hallo vom Laravel Backend!';
});


/**
 * AUTH ROUTES
 */
Route::middleware(['auth:sanctum'])->group(function () {

    // Testroute: gibt den authentifizierten User zurück
    Route::get('/users/auth', function () {
        return User::findOrFail(auth()->id());
    });
  });