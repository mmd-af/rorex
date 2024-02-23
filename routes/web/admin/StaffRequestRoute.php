<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'App\Http\Controllers\Admin\StaffRequest'], function () {
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'staffRequests', 'as' => 'staffRequests.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'StaffRequestController@index'
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
            Route::post('/getUserWithRole', [
                'as' => 'getUserWithRole',
                'uses' => 'StaffRequestAjaxController@getUserWithRole'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'StaffRequestAjaxController@store'
            ]);
        });
    });
});
