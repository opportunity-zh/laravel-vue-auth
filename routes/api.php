<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TokenController;

Route::post('/sanctum/token', TokenController::class);

Route::get("/test-me", function () {
    return 'Hallo vom Laravel Backend!';
});


/**
 * AUTH ROUTES
 */
Route::middleware(['auth:sanctum'])->group(function () {

      Route::get('/users/{id}', function ($id) {
        return User::findOrFail($id);
    });
  });