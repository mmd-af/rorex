<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Role'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'RoleController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'RoleController@store'
            ]);
            Route::put('{role}/update', [
                'as' => 'update',
                'uses' => 'RoleController@update'
            ]);
        });
        Route::group(['prefix' => 'roles-ajax', 'as' => 'roles.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'RoleAjaxController@getDataTable'
            ]);
            Route::get('/getPermissions', [
                'as' => 'getPermissions',
                'uses' => 'RoleAjaxController@getPermissions'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'RoleAjaxController@show'
            ]);
            Route::post('/destroy', [
                'as' => 'destroy',
                'uses' => 'RoleAjaxController@destroy'
            ]);
        });
    });
});
