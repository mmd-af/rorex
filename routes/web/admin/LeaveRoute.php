<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:daily_reports'], 'namespace' => 'App\Http\Controllers\Admin\Leave'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'leaves', 'as' => 'leaves.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'LeaveController@index'
            ]);
            Route::get('/export', [
                'as' => 'export',
                'uses' => 'LeaveController@export'
            ]);
        
        });
        Route::group(['prefix' => 'leaves-ajax', 'as' => 'leaves.ajax.'], function () {
           
        });
    });
});