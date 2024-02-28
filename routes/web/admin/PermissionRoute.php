<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Permission'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'PermissionController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'PermissionController@store'
            ]);
            Route::put('{permission}/update', [
                'as' => 'update',
                'uses' => 'PermissionController@update'
            ]);
        });
        Route::group(['prefix' => 'permissions-ajax', 'as' => 'permissions.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'PermissionAjaxController@getDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'PermissionAjaxController@show'
            ]);
            Route::post('/destroy', [
                'as' => 'destroy',
                'uses' => 'PermissionAjaxController@destroy'
            ]);
        });
    });
});
