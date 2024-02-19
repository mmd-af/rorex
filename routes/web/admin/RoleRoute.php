<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Role'], function () {
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
        });
        Route::group(['prefix' => 'roles-ajax', 'as' => 'roles.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'RoleAjaxController@getDataTable'
            ]);
            Route::post('/destroy', [
                'as' => 'destroy',
                'uses' => 'RoleAjaxController@destroy'
            ]);
        });
    });
});
