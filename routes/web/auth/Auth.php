<?php

use App\Http\Controllers\CompanyAuth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyAuth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->prefix('companies/')->name('companies.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});