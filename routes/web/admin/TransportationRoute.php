<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:transportations_control'], 'namespace' => 'App\Http\Controllers\Admin\Transportation'], function () {
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
            Route::post('/acceptOrder', [
                'as' => 'acceptOrder',
                'uses' => 'TransportationController@acceptOrder'
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
            Route::post('/showCompaniesOrder', [
                'as' => 'showCompaniesOrder',
                'uses' => 'TransportationAjaxController@showCompaniesOrder'
            ]);
            Route::post('/active', [
                'as' => 'active',
                'uses' => 'TransportationAjaxController@active'
            ]);
            Route::get('/getTrucks', [
                'as' => 'getTrucks',
                'uses' => 'TransportationAjaxController@getTrucks'
            ]);
            Route::post('/getCompaniesWithTruck', [
                'as' => 'getCompaniesWithTruck',
                'uses' => 'TransportationAjaxController@getCompaniesWithTruck'
            ]);
            Route::post('/getOrderInformations', [
                'as' => 'getOrderInformations',
                'uses' => 'TransportationAjaxController@getOrderInformations'
            ]);
        });
    });
});
