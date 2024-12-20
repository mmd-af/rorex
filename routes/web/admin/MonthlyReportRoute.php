<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:monthly_reports'], 'namespace' => 'App\Http\Controllers\Admin\MonthlyReport'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'monthlyReports', 'as' => 'monthlyReports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'MonthlyReportController@index'
            ]);
            Route::post('/fullExport', [
                'as' => 'fullExport',
                'uses' => 'MonthlyReportController@fullExport'
            ]);
            Route::post('/userMonthlyReportExport', [
                'as' => 'userMonthlyReportExport',
                'uses' => 'MonthlyReportController@userMonthlyReportExport'
            ]);
        });
        Route::group(['prefix' => 'monthlyReports-ajax', 'as' => 'monthlyReports.ajax.'], function () {
            Route::get('/getUserTable', [
                'as' => 'getUserTable',
                'uses' => 'MonthlyReportAjaxController@getUserTable'
            ]);
            Route::post('/monthlyReportWithDate', [
                'as' => 'monthlyReportWithDate',
                'uses' => 'MonthlyReportAjaxController@monthlyReportWithDate'
            ]);
        });
    });
});
