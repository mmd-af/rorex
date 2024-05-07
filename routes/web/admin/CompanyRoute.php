<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'super.admin'], 'namespace' => 'App\Http\Controllers\Admin\Company'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'CompanyController@index'
            ]);
        });
        Route::group(['prefix' => 'companies-ajax', 'as' => 'companies.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'CompanyAjaxController@getDataTable'
            ]);
            Route::post('/show', [
                'as' => 'show',
                'uses' => 'CompanyAjaxController@show'
            ]);
            Route::post('/active', [
                'as' => 'active',
                'uses' => 'CompanyAjaxController@active'
            ]);
        });
    });
});
