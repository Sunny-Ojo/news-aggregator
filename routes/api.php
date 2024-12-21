<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth routes
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('forgot-password', [PasswordController::class, 'sendPasswordResetLink']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);

//articles
Route::get('articles', [ArticleController::class, 'index']);
Route::get('categories', [ArticleController::class, 'categories']);
Route::get('authors', [ArticleController::class, 'authors']);
Route::get('sources', [ArticleController::class, 'sources']);

//user preferences
Route::middleware('auth:sanctum')->prefix('user')->group(function () {

    Route::get('personalized-feed', [ArticleController::class, 'personalizedFeed']);
    Route::get('preferences', [UserPreferenceController::class, 'show']);
    Route::put('preferences', [UserPreferenceController::class, 'update']);
});
