<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\User\Support'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'supports', 'as' => 'supports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'SupportController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'SupportController@store'
            ]);
        });
        Route::group(['prefix' => 'supports-ajax', 'as' => 'supports.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'SupportAjaxController@getDataTable'
            ]);
            Route::get('/getRoles', [
                'as' => 'getRoles',
                'uses' => 'SupportAjaxController@getRoles'
            ]);
        });
    });
});
