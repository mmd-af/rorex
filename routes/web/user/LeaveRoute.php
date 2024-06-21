<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:employees'], 'namespace' => 'App\Http\Controllers\User\Leave'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'leaves', 'as' => 'leaves.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'LeaveController@index'
            ]);
        });
        Route::group(['prefix' => 'leaves-ajax', 'as' => 'leaves.ajax.'], function () {
            Route::get('/getDataTable', [
                'as' => 'getDataTable',
                'uses' => 'LeaveAjaxController@getDataTable'
            ]);
            Route::post('/store', [
                'as' => 'store',
                'uses' => 'LeaveAjaxController@store'
            ]);
        });
    });
});
