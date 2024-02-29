<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'permission:employees'], 'namespace' => 'App\Http\Controllers\User\MonthlyReport'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'monthlyReports', 'as' => 'monthlyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'MonthlyReportController@index'
            ]);
            Route::post('/userMonthlyReportExport', [
                'as' => 'userMonthlyReportExport',
                'uses' => 'MonthlyReportController@userMonthlyReportExport'
            ]);
        });
        Route::group(['prefix' => 'monthlyReports-ajax', 'as' => 'monthlyReports.ajax.'], function () {
            Route::post('/monthlyReportWithDate', [
                'as' => 'monthlyReportWithDate',
                'uses' => 'MonthlyReportAjaxController@monthlyReportWithDate'
            ]);
        });
    });
});
