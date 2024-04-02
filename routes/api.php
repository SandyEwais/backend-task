<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sign-up', [AuthController::class,'signUp']);

Route::post('/login', [AuthController::class,'login']);

Route::get('email/verify/{id}', [AuthController::class,'verify'])->name('verification.verify');

Route::get('email/resend', [AuthController::class,'resend'])->name('verification.resend');

