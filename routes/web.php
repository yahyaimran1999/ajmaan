<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\StripeController;

Route::get('api/auth/google', [AuthController::class, 'redirectToGoogle'])->middleware('web');
Route::get('api/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('web');

Route::get('api/auth/apple', [AuthController::class, 'redirectToApple'])->middleware('web');
Route::get('api/auth/apple/callback', [AuthController::class, 'handleAppleCallback'])->middleware('web');

Route::get('api/auth/facebook', [AuthController::class, 'redirectToFacebook'])->middleware('web');
Route::get('api/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->middleware('web');

Route::get('/subscription/success', [StripeController::class, 'subscriptionSuccess'])->name('subscription.success');
Route::get('/subscription/cancel', [StripeController::class, 'subscriptionCancel'])->name('subscription.cancel');