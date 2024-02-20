<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\User\StaffRequest'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'staffRequests', 'as' => 'staffRequests.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'StaffRequestController@index'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'StaffRequestController@store'
            ]);
        });
        Route::group(['prefix' => 'staffRequests-ajax', 'as' => 'staffRequests.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'StaffRequestAjaxController@getDataTable'
            ]);
            Route::get('/getRoles', [
                'as' => 'getRoles',
                'uses' => 'StaffRequestAjaxController@getRoles'
            ]);
        });
    });
});
