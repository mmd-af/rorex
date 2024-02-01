<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\Dailyreport'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'dailyReports', 'as' => 'dailyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DailyreportController@index'
            ]);
            Route::post('/filter', [
                'as' => 'filter',
                'uses' => 'DailyreportController@filter'
            ]);
            Route::post('/supportRequest', [
                'as' => 'supportRequest',
                'uses' => 'DailyreportController@supportRequest'
            ]);
            Route::put('/import', [
                'as' => 'import',
                'uses' => 'DailyreportController@import'
            ]);
        });
        Route::group(['prefix' => 'dailyReports-ajax', 'as' => 'dailyReports.ajax.'], function () {
            Route::post('/getData', [
                'as' => 'getData',
                'uses' => 'DailyReportAjaxController@getData'
            ]);
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'DailyReportAjaxController@getDataTable'
            ]);
        });
    });
});
