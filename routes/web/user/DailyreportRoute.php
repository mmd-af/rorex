<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\User\Dailyreport'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'dailyReports', 'as' => 'dailyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DailyreportController@index'
            ]);
            Route::post('/filter', [
                'as' => 'filter',
                'uses' => 'DailyreportController@filter'
            ]);
        });
        Route::group(['prefix' => 'dailyReports-ajax', 'as' => 'dailyReports.ajax.'], function () {

        });
    });
});
