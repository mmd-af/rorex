<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:employees'], 'namespace' => 'App\Http\Controllers\User\DailyReport'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'dailyReports', 'as' => 'dailyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'DailyReportController@index'
            ]);
            Route::post('/checkRequest', [
                'as' => 'checkRequest',
                'uses' => 'DailyReportController@checkRequest'
            ]);
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
            Route::get('/getRoles', [
                'as' => 'getRoles',
                'uses' => 'DailyReportAjaxController@getRoles'
            ]);
            Route::post('/getUserWithRole', [
                'as' => 'getUserWithRole',
                'uses' => 'DailyReportAjaxController@getUserWithRole'
            ]);
            // Route::post('/getLastUpdate', [
            //     'as' => 'getLastUpdate',
            //     'uses' => 'DailyReportAjaxController@getLastUpdate'
            // ]);
        });
    });
});
