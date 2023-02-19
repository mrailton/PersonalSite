<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Articles\CreateArticleController;
use App\Http\Controllers\Admin\Articles\EditArticleController;
use App\Http\Controllers\Admin\Articles\ListArticlesController as AdminListArticlesController;
use App\Http\Controllers\Admin\Articles\StoreArticleController;
use App\Http\Controllers\Admin\Articles\UpdateArticleController;
use App\Http\Controllers\Admin\Certificates\CreateCertificateController;
use App\Http\Controllers\Admin\Certificates\DeleteCertificateController;
use App\Http\Controllers\Admin\Certificates\DownloadCertificateController;
use App\Http\Controllers\Admin\Certificates\EditCertificateController;
use App\Http\Controllers\Admin\Certificates\ListCertificatesController;
use App\Http\Controllers\Admin\Certificates\ShowCertificateController;
use App\Http\Controllers\Admin\Certificates\StoreCertificateController;
use App\Http\Controllers\Admin\Certificates\UpdateCertificateController;
use App\Http\Controllers\Admin\Certificates\ViewCertificateFileController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Articles\ListArticlesController;
use App\Http\Controllers\Articles\ShowArticleController;
use App\Http\Controllers\Auth\CreateLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\StoreLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class)->name('index');

Route::get('/blog', ListArticlesController::class)->name('articles.list');
Route::get('/blog/{article:slug}', ShowArticleController::class)->name('articles.show');

Route::prefix('/login')->name('login.')->middleware('guest')->group(function () {
    Route::get('/', CreateLoginController::class)->name('create');
    Route::post('/', StoreLoginController::class)->name('store');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', LogoutController::class)->name('auth.logout');

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::prefix('/articles')->name('articles.')->group(function () {
            Route::post('/', StoreArticleController::class)->name('store');
            Route::get('/', AdminListArticlesController::class)->name('list');
            Route::get('/create', CreateArticleController::class)->name('create');
            Route::put('/{article:slug}', UpdateArticleController::class)->name('update');
            Route::get('/{article:slug}/edit', EditArticleController::class)->name('edit');
        });

        Route::prefix('/certificates')->name('certificates.')->group(function () {
            Route::get('/', ListCertificatesController::class)->name('list');
            Route::get('/create', CreateCertificateController::class)->name('create');
            Route::post('/', StoreCertificateController::class)->name('store');
            Route::get('/{certificate:id}', ShowCertificateController::class)->name('show');
            Route::get('/{certificate:id}/download', DownloadCertificateController::class)->name('download');
            Route::get('/{certificate:id}/view-certificate', ViewCertificateFileController::class)->name('view-certificate');
            Route::get('/{certificate:id}/edit', EditCertificateController::class)->name('edit');
            Route::put('/{certificate:id}', UpdateCertificateController::class)->name('update');
            Route::delete('/{certificate:id}', DeleteCertificateController::class)->name('delete');
        });

        Route::prefix('/customers')->name('customers.')->group(function () {
            Route::get('/', [CustomersController::class, 'list'])->name('list');
            Route::get('/create', [CustomersController::class, 'create'])->name('create');
            Route::post('/', [CustomersController::class, 'store'])->name('store');
        });
    });
});
