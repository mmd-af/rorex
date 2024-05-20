<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:trucks_control'], 'namespace' => 'App\Http\Controllers\Admin\Truck'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'trucks', 'as' => 'trucks.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'TruckController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'TruckController@store'
            ]);
        });
        Route::group(['prefix' => 'trucks-ajax', 'as' => 'trucks.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'TruckAjaxController@getDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'TruckAjaxController@show'
            ]);
            Route::post('/active', [
                'as' => 'active',
                'uses' => 'TruckAjaxController@active'
            ]);
        });
    });
});
