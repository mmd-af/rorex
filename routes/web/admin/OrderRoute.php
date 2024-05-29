<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:orders_control'], 'namespace' => 'App\Http\Controllers\Admin\Order'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'OrderController@index'
            ]);
            Route::get('/{order}/show', [
                'as' => 'show',
                'uses' => 'OrderController@show'
            ]);
            Route::post('/uploadCmr', [
                'as' => 'uploadCmr',
                'uses' => 'OrderController@uploadCmr'
            ]);
        });
        Route::group(['prefix' => 'orders-ajax', 'as' => 'orders.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'OrderAjaxController@getDataTable'
            ]);
        });
    });
});
