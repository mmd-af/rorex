<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\Support'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'supports', 'as' => 'supports.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'SupportController@index'
            ]);
            Route::put('/archiveMessage', [
                'as' => 'archiveMessage',
                'uses' => 'SupportController@archiveMessage'
            ]);
        });
        Route::group(['prefix' => 'supports-ajax', 'as' => 'supports.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'SupportAjaxController@getDataTable'
            ]);
            Route::post('/storeReaded', [
                'as' => 'storeReaded',
                'uses' => 'SupportAjaxController@storeReaded'
            ]);
        });
    });
});
