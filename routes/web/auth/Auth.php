<?php

use App\Http\Controllers\CompanyAuth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->prefix('companies/')->name('companies.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});