<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web','check_user_role'], 'namespace' => 'App\Http\Controllers\Admin\Dashboard'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DashboardController@index'
            ]);
        });
    });
});
