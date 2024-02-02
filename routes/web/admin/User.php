<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\User'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'UserController@index'
            ]);
            Route::put('/import', [
                'as' => 'import',
                'uses' => 'UserController@import'
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
        });
    });
});
