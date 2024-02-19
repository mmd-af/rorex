<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Permission'], function () {
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
        });
        Route::group(['prefix' => 'permissions-ajax', 'as' => 'permissions.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'PermissionAjaxController@getDataTable'
            ]);
        });
    });
});
