<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\User'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'UserController@index'
            ]);
            Route::put('{user}/update', [
                'as' => 'update',
                'uses' => 'UserController@update'
            ]);
            Route::put('/import', [
                'as' => 'import',
                'uses' => 'UserController@import'
            ]);
            Route::get('/leaveBalance', [
                'as' => 'leaveBalance',
                'uses' => 'UserController@leaveBalance'
            ]);
            Route::put('{user}/updateLeaveBalance', [
                'as' => 'updateLeaveBalance',
                'uses' => 'UserController@updateLeaveBalance'
            ]);
        });
        Route::group(['prefix' => 'users-ajax', 'as' => 'users.ajax.'], function () {
            Route::post('/getData', [
                'as' => 'getData',
                'uses' => 'UserAjaxController@getData'
            ]);
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'UserAjaxController@getDataTable'
            ]);
            Route::get('/getLeaveBalanceData', [
                'as' => 'getLeaveBalanceData',
                'uses' => 'UserAjaxController@getLeaveBalanceData'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'UserAjaxController@show'
            ]);
        });
    });
});
