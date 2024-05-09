<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Transportation'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'transportations', 'as' => 'transportations.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'TransportationController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'TransportationController@store'
            ]);
        });
        Route::group(['prefix' => 'transportations-ajax', 'as' => 'transportations.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'TransportationAjaxController@getDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'TransportationAjaxController@show'
            ]);
            Route::post('/active', [
                'as' => 'active',
                'uses' => 'TransportationAjaxController@active'
            ]);
            Route::get('/getTruck', [
                'as' => 'getTruck',
                'uses' => 'TransportationAjaxController@getTruck'
            ]);
        });
    });
});
