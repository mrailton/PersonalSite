<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::post(uri: '/auth/login', action: LoginController::class);
