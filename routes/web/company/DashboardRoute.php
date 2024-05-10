<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Company\Dashboard'], function () {
    Route::group(['prefix' => 'company', 'as' => 'company.'], function () {
        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DashboardController@index'
            ]);
        });
        Route::group(['prefix' => 'dashboard-ajax', 'as' => 'dashboard.ajax.'], function () {
            // Route::get('/getTruck', [
            //     'as' => 'getTruck',
            //     'uses' => 'DashboardAjaxController@getTruck'
            // ]);
        });
    });
});
