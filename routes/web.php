<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Articles\ListArticlesController;
use App\Http\Controllers\Articles\ShowArticleController;
use App\Http\Controllers\Auth\CreateLoginController;
use App\Http\Controllers\Auth\StoreLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class)->name('index');

Route::get('/blog', ListArticlesController::class)->name('articles.list');
Route::get('/blog/{article:slug}', ShowArticleController::class)->name('articles.show');

Route::get('/login', CreateLoginController::class)->name('login.create');
Route::post('/login', StoreLoginController::class)->name('login.store');

Route::prefix('/admin')->name('admin.')->middleware('auth:web')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
});
