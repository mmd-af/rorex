<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth','permission:daily_reports'], 'namespace' => 'App\Http\Controllers\Admin\DailyReport'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dailyReports', 'as' => 'dailyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DailyReportController@index'
            ]);
            Route::put('/import', [
                'as' => 'import',
                'uses' => 'DailyReportController@import'
            ]);
        });
        Route::group(['prefix' => 'dailyReports-ajax', 'as' => 'dailyReports.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'DailyReportAjaxController@getDataTable'
            ]);
        });
    });
});
