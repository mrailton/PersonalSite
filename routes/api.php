<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Articles\ListArticlesController;
use App\Http\Controllers\Api\Auth\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::post(uri: '/auth/login', action: AuthenticateController::class)->name('authenticate');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/articles')->name('articles:')->group(function () {
        Route::get('/', ListArticlesController::class)->name('list');
    });
});
