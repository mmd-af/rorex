<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'permission:daily_reports'], 'namespace' => 'App\Http\Controllers\Admin\DailyReport'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dailyReports', 'as' => 'dailyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DailyReportController@index'
            ]);
            Route::put('{dailyID}/update', [
                'as' => 'update',
                'uses' => 'DailyReportController@update'
            ])->middleware('permission:update_daily_reports');
            Route::put('/import', [
                'as' => 'import',
                'uses' => 'DailyReportController@import'
            ])->middleware('permission:update_daily_reports');

            Route::group(['prefix' => 'singleReports', 'as' => 'singleReports.'], function () {
                Route::get('/', [
                    'as' => 'index',
                    'uses' => 'DailyReportController@indexSingleReport'
                ]);
                Route::post('/import', [
                    'as' => 'import',
                    'uses' => 'DailyReportController@importSingleReport'
                ])->middleware('permission:update_daily_reports');
            });
        });
        Route::group(['prefix' => 'dailyReports-ajax', 'as' => 'dailyReports.ajax.'], function () {
            Route::post('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'DailyReportAjaxController@getDataTable'
            ]);
            Route::post('/getData', [
                'as' => 'getData',
                'uses' => 'DailyReportAjaxController@getData'
            ]);
            Route::post('/renderTimeForm', [
                'as' => 'renderTimeForm',
                'uses' => 'DailyReportAjaxController@renderTimeForm'
            ]);
        });
    });
});
